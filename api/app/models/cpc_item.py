import uuid

from sqlalchemy import Column, String, Date, Text, INT, func, DateTime

from app import Base


class CpcItem(Base):
    __tablename__ = 'cpc_items'

    id = Column(String(36), primary_key=True, default=lambda: str(uuid.uuid4()))
    item_type = Column(String(255), nullable=False)
    date = Column(Date, nullable=False)
    name = Column(String(255), nullable=False)
    topics = Column(Text, nullable=False)
    key_learning_outcomes = Column(Text, nullable=False)
    points = Column(INT, nullable=False)
    practice_change = Column(Text, nullable=False)
    created_at = Column(DateTime, nullable=False, default=func.now())
    updated_at = Column(DateTime, nullable=False, default=func.now(), onupdate=func.now())
