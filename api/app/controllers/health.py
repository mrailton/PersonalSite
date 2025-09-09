from datetime import datetime
from fastapi import APIRouter

from app.schemas.health import HealthSchema

router = APIRouter(tags=["health"])


@router.get("/health", response_model=HealthSchema)
def get_health():
    return {
        'status': 'ok',
        'timestamp': datetime.now()
    }