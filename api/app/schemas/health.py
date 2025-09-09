from datetime import datetime
from pydantic import BaseModel


class HealthSchema(BaseModel):
    status: str
    timestamp: datetime