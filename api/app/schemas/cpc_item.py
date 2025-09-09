from datetime import date as Date, datetime
from typing import Optional

from pydantic import BaseModel, Field


class BaseCPCItemSchema(BaseModel):
    id: str
    name: str
    item_type: str
    date: Date
    points: int
    created_at: datetime
    updated_at: datetime

    model_config = {"from_attributes": True}


class CPCItemSchema(BaseCPCItemSchema):
    topics: str
    key_learning_outcomes: str
    practice_change: str


class CPCItemCreateSchema(BaseModel):
    name: str = Field(..., min_length=1, max_length=255, description="CPC item name")
    item_type: str = Field(..., min_length=1, max_length=255, description="Type of CPC item")
    date: Date = Field(..., description="Date of the CPC activity")
    topics: str = Field(..., min_length=1, description="Topics covered")
    key_learning_outcomes: str = Field(..., min_length=1, description="Key learning outcomes")
    points: int = Field(..., ge=0, description="Points earned")
    practice_change: str = Field(..., min_length=1, description="Practice change description")


class CPCItemUpdateSchema(BaseModel):
    name: Optional[str] = Field(None, min_length=1, max_length=255, description="CPC item name")
    item_type: Optional[str] = Field(None, min_length=1, max_length=255, description="Type of CPC item")
    date: Optional[Date] = Field(None, description="Date of the CPC activity")
    topics: Optional[str] = Field(None, min_length=1, description="Topics covered")
    key_learning_outcomes: Optional[str] = Field(None, min_length=1, description="Key learning outcomes")
    points: Optional[int] = Field(None, ge=0, description="Points earned")
    practice_change: Optional[str] = Field(None, min_length=1, description="Practice change description")