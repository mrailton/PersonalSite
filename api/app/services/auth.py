from datetime import datetime, timedelta, timezone
from typing import Optional

from fastapi import Depends, HTTPException, status, Security
from fastapi.security import HTTPBearer, HTTPAuthorizationCredentials
from jose import JWTError, jwt
from passlib.context import CryptContext
from pydantic import EmailStr
from sqlalchemy.orm import Session

from app import get_db
from app.models.user import User
from config import Config

pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto")
SECRET_KEY = Config.SECRET_KEY
ALGORITHM = "HS256"
ACCESS_TOKEN_EXPIRE_MINUTES = 30

security = HTTPBearer()


def verify_password(plain_password: str, hashed_password: str) -> bool:
    return pwd_context.verify(plain_password, hashed_password)


def get_password_hash(password: str) -> str:
    return pwd_context.hash(password)


def create_access_token(data: dict, expires_delta: Optional[timedelta] = None):
    to_encode = data.copy()
    if expires_delta:
        expire = datetime.now(timezone.utc) + expires_delta
    else:
        expire = datetime.now(timezone.utc) + timedelta(minutes=ACCESS_TOKEN_EXPIRE_MINUTES)
    to_encode.update({"exp": expire})
    encoded_jwt = jwt.encode(to_encode, SECRET_KEY, algorithm=ALGORITHM)
    return encoded_jwt


def verify_token(token: str) -> Optional[dict]:
    try:
        payload = jwt.decode(token, SECRET_KEY, algorithms=[ALGORITHM])
        return payload
    except JWTError:
        return None


def authenticate_user(db: Session, email: EmailStr, password: str) -> type[User] | None:
    user = db.query(User).filter(User.email == email).first()

    if not user:
        return None
    if not verify_password(password, str(user.password)):
        return None
    return user


def get_authenticated_user(required: bool = True):
    """Get the authenticated user from JWT token.
    
    Args:
        required: If True, raises HTTPException on authentication failure.
                 If False, returns None on failure.
    
    Returns:
        A FastAPI dependency that returns User object if authenticated, 
        None if not authenticated and required=False
        
    Raises:
        HTTPException: If authentication fails and required=True
    """

    def _get_authenticated_user(
            credentials: Optional[HTTPAuthorizationCredentials] = Depends(
                security if required else HTTPBearer(auto_error=False)
            ),
            db: Session = Depends(get_db)
    ) -> Optional[type[User]]:
        if credentials is None:
            if required:
                raise HTTPException(
                    status_code=status.HTTP_401_UNAUTHORIZED,
                    detail="Could not validate credentials",
                    headers={"WWW-Authenticate": "Bearer"},
                )
            return None

        token = credentials.credentials
        payload = verify_token(token)
        if payload is None:
            if required:
                raise HTTPException(
                    status_code=status.HTTP_401_UNAUTHORIZED,
                    detail="Could not validate credentials",
                    headers={"WWW-Authenticate": "Bearer"},
                )
            return None

        email: str = payload.get("sub")
        if email is None:
            if required:
                raise HTTPException(
                    status_code=status.HTTP_401_UNAUTHORIZED,
                    detail="Could not validate credentials",
                    headers={"WWW-Authenticate": "Bearer"},
                )
            return None

        user = db.query(User).filter(User.email == email).first()
        if user is None and required:
            raise HTTPException(
                status_code=status.HTTP_401_UNAUTHORIZED,
                detail="Could not validate credentials",
                headers={"WWW-Authenticate": "Bearer"},
            )

        return user

    return Depends(_get_authenticated_user)

require_auth = Security(security)
optional_auth = Security(HTTPBearer(auto_error=False))
