from flask import Flask


def create_app():
    app = Flask(__name__)

    __register_blueprints(app)

    return app


def __register_blueprints(app: Flask):
    from app.routes import public_bp

    app.register_blueprint(public_bp)
