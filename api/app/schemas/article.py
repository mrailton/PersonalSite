from datetime import datetime
from typing import Optional
from pydantic import BaseModel, Field


class BaseArticleSchema(BaseModel):
    id: str
    title: str
    slug: str
    published_at: Optional[datetime] = None
    created_at: datetime
    updated_at: datetime

    model_config = {"from_attributes": True}


class ArticleSchema(BaseArticleSchema):
    content: str


class ArticleCreateSchema(BaseModel):
    title: str = Field(..., min_length=1, max_length=255, description="Article title")
    slug: str = Field(..., min_length=1, max_length=255, description="URL-friendly slug")
    content: str = Field(..., min_length=1, description="Article content")
    published_at: Optional[datetime] = Field(None, description="Publication date and time")


class ArticleUpdateSchema(BaseModel):
    title: Optional[str] = Field(None, min_length=1, max_length=255, description="Article title")
    content: Optional[str] = Field(None, min_length=1, description="Article content")
    published_at: Optional[datetime] = Field(None, description="Publication date and time")


class ArticlesQueryArgsSchema(BaseModel):
    limit: Optional[int] = Field(
        None, 
        gt=0, 
        description="Maximum number of articles to return"
    )