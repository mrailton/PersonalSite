from datetime import datetime, timezone
from typing import List, Optional

from fastapi import APIRouter, Depends, HTTPException, Query
from sqlalchemy.orm import Session

from app import get_db
from app.models.article import Article
from app.schemas.article import BaseArticleSchema, ArticleSchema, ArticleCreateSchema, ArticleUpdateSchema
from app.services.auth import require_auth, optional_auth

router = APIRouter(tags=["articles"])


@router.get("/articles", response_model=List[BaseArticleSchema])
def get_articles(
        limit: Optional[int] = Query(None, gt=0, description="Maximum number of articles to return"),
        db: Session = Depends(get_db),
        _token: Optional[str] = optional_auth
):
    query = db.query(Article)

    if _token is None:
        query = query.filter(Article.published_at <= datetime.now(timezone.utc)).order_by(Article.published_at.desc())
    else:
        query = query.order_by(Article.created_at.desc())

    if limit and limit > 0:
        query = query.limit(limit)

    return query.all()


@router.get("/articles/{slug}", response_model=ArticleSchema)
def get_article(
        slug: str,
        db: Session = Depends(get_db),
        _token: Optional[str] = optional_auth
):
    query = db.query(Article).filter_by(slug=slug)

    if _token is None:
        query = query.filter(Article.published_at <= datetime.now(timezone.utc))

    article = query.first()

    if not article:
        raise HTTPException(status_code=404, detail="Article not found")

    return article


@router.post("/articles", response_model=ArticleSchema, status_code=201)
def create_article(
    article_data: ArticleCreateSchema,
    db: Session = Depends(get_db),
    _token: str = require_auth
):
    existing_article = db.query(Article).filter_by(slug=article_data.slug).first()
    if existing_article:
        raise HTTPException(status_code=400, detail="Article with this slug already exists")
    
    article = Article(
        title=article_data.title,
        slug=article_data.slug,
        content=article_data.content,
        published_at=article_data.published_at
    )
    
    db.add(article)
    db.commit()
    db.refresh(article)
    
    return article


@router.put("/articles/{article_id}", response_model=ArticleSchema)
def update_article(
    article_id: str,
    update_data: ArticleUpdateSchema,
    db: Session = Depends(get_db),
    _token: str = require_auth
):
    article = db.query(Article).filter(Article.id == article_id).first()
    if not article:
        raise HTTPException(status_code=404, detail="Article not found")
    
    update_dict = update_data.model_dump(exclude_unset=True)
    
    for field, value in update_dict.items():
        setattr(article, field, value)
    
    db.commit()
    db.refresh(article)
    
    return article


@router.delete("/articles/{article_id}", status_code=204)
def delete_article(
    article_id: str,
    db: Session = Depends(get_db),
    _token: str = require_auth
):
    article = db.query(Article).filter(Article.id == article_id).first()
    if not article:
        raise HTTPException(status_code=404, detail="Article not found")
    
    db.delete(article)
    db.commit()
    
    return
