from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker, declarative_base

from config import Config

Base = declarative_base()

engine = None
SessionLocal = None


def create_app(config_class=Config):
    global engine, SessionLocal

    app = FastAPI(
        title=config_class.API_TITLE,
        version=config_class.API_VERSION,
        docs_url=config_class.OPENAPI_SWAGGER_UI_PATH,
    )

    app.add_middleware(
        CORSMiddleware,
        allow_origins=["*"],
        allow_credentials=True,
        allow_methods=["*"],
        allow_headers=["*"],
    )

    # Setup database
    engine = create_engine(config_class.SQLALCHEMY_DATABASE_URI)
    SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)

    from app.controllers.health import router as health_router
    from app.controllers.articles import router as articles_router
    from app.controllers.auth import router as auth_router
    from app.controllers.cpc import router as cpc_router

    app.include_router(health_router)
    app.include_router(articles_router)
    app.include_router(auth_router)
    app.include_router(cpc_router)

    return app


def get_db():
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()
