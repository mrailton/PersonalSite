from datetime import timedelta

from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.orm import Session

from app import get_db
from app.schemas.auth import LoginRequest, LoginResponse, UserResponse
from app.services.auth import authenticate_user, create_access_token, \
    ACCESS_TOKEN_EXPIRE_MINUTES, get_authenticated_user

router = APIRouter(prefix="/auth", tags=["authentication"])


@router.post("/login", response_model=LoginResponse)
def login(
    login_data: LoginRequest,
    db: Session = Depends(get_db)
):
    """Authenticate user and return access token."""
    user = authenticate_user(db, login_data.email, login_data.password)
    if not user:
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Incorrect email or password",
            headers={"WWW-Authenticate": "Bearer"},
        )
    
    access_token_expires = timedelta(minutes=ACCESS_TOKEN_EXPIRE_MINUTES)
    access_token = create_access_token(
        data={"sub": user.email},
        expires_delta=access_token_expires
    )
    
    return LoginResponse(access_token=access_token)


@router.get("/me", response_model=UserResponse)
def get_authenticated_user_info(
    authenticated_user = get_authenticated_user(required=True)
):
    """Get current authenticated user information."""
    return UserResponse(
        id=authenticated_user.id,
        name=authenticated_user.name,
        email=authenticated_user.email,
        created_at=authenticated_user.created_at.isoformat()
    )