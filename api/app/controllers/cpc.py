from typing import List

from fastapi import APIRouter, Depends, HTTPException
from sqlalchemy.orm import Session

from app import get_db
from app.models.cpc_item import CpcItem
from app.schemas.cpc_item import BaseCPCItemSchema, CPCItemSchema, CPCItemCreateSchema, CPCItemUpdateSchema
from app.services.auth import require_auth

router = APIRouter(tags=["cpc"])

@router.get("/cpc", response_model=List[BaseCPCItemSchema])
def get_cpc_items(db: Session = Depends(get_db), _token: str = require_auth):
    query = db.query(CpcItem).order_by(CpcItem.date.desc())
    return query.all()


@router.get("/cpc/{cpc_item_id}", response_model=CPCItemSchema)
def get_cpc_item(
    cpc_item_id: str,
    db: Session = Depends(get_db),
    _token: str = require_auth
):
    cpc_item = db.query(CpcItem).filter(CpcItem.id == cpc_item_id).first()
    
    if not cpc_item:
        raise HTTPException(status_code=404, detail="CPC item not found")
    
    return cpc_item


@router.post("/cpc", response_model=CPCItemSchema, status_code=201)
def create_cpc_item(
    cpc_item_data: CPCItemCreateSchema,
    db: Session = Depends(get_db),
    _token: str = require_auth
):
    cpc_item = CpcItem(
        name=cpc_item_data.name,
        item_type=cpc_item_data.item_type,
        date=cpc_item_data.date,
        topics=cpc_item_data.topics,
        key_learning_outcomes=cpc_item_data.key_learning_outcomes,
        points=cpc_item_data.points,
        practice_change=cpc_item_data.practice_change
    )
    
    db.add(cpc_item)
    db.commit()
    db.refresh(cpc_item)
    
    return cpc_item


@router.put("/cpc/{cpc_item_id}", response_model=CPCItemSchema)
def update_cpc_item(
    cpc_item_id: str,
    update_data: CPCItemUpdateSchema,
    db: Session = Depends(get_db),
    _token: str = require_auth
):
    cpc_item = db.query(CpcItem).filter(CpcItem.id == cpc_item_id).first()
    if not cpc_item:
        raise HTTPException(status_code=404, detail="CPC item not found")
    
    update_dict = update_data.model_dump(exclude_unset=True)
    
    for field, value in update_dict.items():
        setattr(cpc_item, field, value)
    
    db.commit()
    db.refresh(cpc_item)
    
    return cpc_item


@router.delete("/cpc/{cpc_item_id}", status_code=204)
def delete_cpc_item(
    cpc_item_id: str,
    db: Session = Depends(get_db),
    _token: str = require_auth
):
    cpc_item = db.query(CpcItem).filter(CpcItem.id == cpc_item_id).first()
    if not cpc_item:
        raise HTTPException(status_code=404, detail="CPC item not found")
    
    db.delete(cpc_item)
    db.commit()
    
    return

