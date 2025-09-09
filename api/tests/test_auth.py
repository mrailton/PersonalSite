import pytest

from app.services.auth import create_access_token


class TestLogin:

    def test_success(self, client, test_user):
        login_data = {
            "email": "test@example.com",
            "password": "testpassword"
        }
        response = client.post("/auth/login", json=login_data)
        
        assert response.status_code == 200
        data = response.json()
        assert "access_token" in data
        assert data["token_type"] == "bearer"
        assert len(data["access_token"]) > 0

    def test_invalid_email(self, client):
        login_data = {
            "email": "nonexistent@example.com",
            "password": "testpassword"
        }
        response = client.post("/auth/login", json=login_data)
        
        assert response.status_code == 401
        assert "Incorrect email or password" in response.json()["detail"]

    def test_invalid_password(self, client, test_user):
        login_data = {
            "email": "test@example.com",
            "password": "wrongpassword"
        }
        response = client.post("/auth/login", json=login_data)
        
        assert response.status_code == 401
        assert "Incorrect email or password" in response.json()["detail"]

    def test_missing_fields(self, client):
        response = client.post("/auth/login", json={"email": "test@example.com"})
        assert response.status_code == 422
        
        response = client.post("/auth/login", json={"password": "testpassword"})
        assert response.status_code == 422

    def test_invalid_email_format(self, client):
        login_data = {
            "email": "invalid-email",
            "password": "testpassword"
        }
        response = client.post("/auth/login", json=login_data)
        assert response.status_code == 422



class TestGetAuthenticatedUser:

    def test_success(self, client, test_user, auth_headers):
        response = client.get("/auth/me", headers=auth_headers)
        
        assert response.status_code == 200
        data = response.json()
        assert data["id"] == test_user.id
        assert data["name"] == test_user.name
        assert data["email"] == test_user.email
        assert "created_at" in data

    def test_no_token(self, client):
        response = client.get("/auth/me")
        assert response.status_code == 403

    def test_invalid_token(self, client):
        headers = {"Authorization": "Bearer invalid_token"}
        response = client.get("/auth/me", headers=headers)
        
        assert response.status_code == 401
        assert "Could not validate credentials" in response.json()["detail"]

    def test_expired_token(self, client, test_user):
        from datetime import datetime, timedelta, timezone
        from jose import jwt
        from app.services.auth import SECRET_KEY, ALGORITHM
        
        expired_token_data = {
            "sub": test_user.email,
            "exp": datetime.now(timezone.utc) - timedelta(seconds=1)
        }
        
        expired_token = jwt.encode(expired_token_data, SECRET_KEY, algorithm=ALGORITHM)
        headers = {"Authorization": f"Bearer {expired_token}"}
        response = client.get("/auth/me", headers=headers)
        
        assert response.status_code == 401

    def test_token_missing_sub_claim(self, client):
        from datetime import datetime, timedelta, timezone
        from jose import jwt
        from app.services.auth import SECRET_KEY, ALGORITHM
        
        token_data_without_sub = {
            "user_id": "123",
            "exp": datetime.now(timezone.utc) + timedelta(minutes=15)
        }
        
        token = jwt.encode(token_data_without_sub, SECRET_KEY, algorithm=ALGORITHM)
        headers = {"Authorization": f"Bearer {token}"}
        response = client.get("/auth/me", headers=headers)
        
        assert response.status_code == 401
        assert "Could not validate credentials" in response.json()["detail"]

    def test_token_with_none_sub_claim(self, client):
        from datetime import datetime, timedelta, timezone
        from jose import jwt
        from app.services.auth import SECRET_KEY, ALGORITHM
        
        token_data_with_none_sub = {
            "sub": None,
            "exp": datetime.now(timezone.utc) + timedelta(minutes=15)
        }
        
        token = jwt.encode(token_data_with_none_sub, SECRET_KEY, algorithm=ALGORITHM)
        headers = {"Authorization": f"Bearer {token}"}
        response = client.get("/auth/me", headers=headers)
        
        assert response.status_code == 401
        assert "Could not validate credentials" in response.json()["detail"]

    def test_demonstrates_unreachable_code_at_line_85(self, client):
        from datetime import datetime, timedelta, timezone
        from jose import jwt
        from app.services.auth import SECRET_KEY, ALGORITHM
        
        token_data_no_sub = {
            "user_id": "123",
            "exp": datetime.now(timezone.utc) + timedelta(minutes=15)
        }
        token = jwt.encode(token_data_no_sub, SECRET_KEY, algorithm=ALGORITHM)
        headers = {"Authorization": f"Bearer {token}"}
        response = client.get("/auth/me", headers=headers)
        
        assert response.status_code == 401
        assert "Could not validate credentials" in response.json()["detail"]
        
        token_data_none_sub = {
            "sub": None,
            "exp": datetime.now(timezone.utc) + timedelta(minutes=15)
        }
        token = jwt.encode(token_data_none_sub, SECRET_KEY, algorithm=ALGORITHM)
        headers = {"Authorization": f"Bearer {token}"}
        response = client.get("/auth/me", headers=headers)
        
        assert response.status_code == 401
        assert "Could not validate credentials" in response.json()["detail"]

    def test_malformed_bearer_token(self, client):
        headers = {"Authorization": "InvalidFormat token"}
        response = client.get("/auth/me", headers=headers)
        
        assert response.status_code == 403

    def test_nonexistent_user(self, client):
        token = create_access_token(data={"sub": "nonexistent@example.com"})
        headers = {"Authorization": f"Bearer {token}"}
        response = client.get("/auth/me", headers=headers)
        
        assert response.status_code == 401
        assert "Could not validate credentials" in response.json()["detail"]


class TestAuthHelpers:

    def test_password_hashing(self):
        from app.services.auth import get_password_hash, verify_password
        
        password = "testpassword123"
        hashed = get_password_hash(password)
        
        assert hashed != password
        assert len(hashed) > 0
        assert verify_password(password, hashed) is True
        assert verify_password("wrongpassword", hashed) is False

    def test_token_creation_and_verification(self):
        from app.services.auth import create_access_token, verify_token
        
        data = {"sub": "test@example.com", "user_id": "123"}
        token = create_access_token(data)
        
        assert len(token) > 0
        
        decoded = verify_token(token)
        assert decoded is not None
        assert decoded["sub"] == "test@example.com"
        assert "exp" in decoded
        
        invalid_decoded = verify_token("invalid.token.here")
        assert invalid_decoded is None

    def test_authenticate_user(self, test_db, test_user):
        from app.services.auth import authenticate_user
        
        user = authenticate_user(test_db, "test@example.com", "testpassword")
        assert user is not None
        assert user.email == "test@example.com"
        
        user = authenticate_user(test_db, "wrong@example.com", "testpassword")
        assert user is None
        
        user = authenticate_user(test_db, "test@example.com", "wrongpassword")
        assert user is None


class TestAuthIntegration:

    def test_full_auth_flow(self, client, test_user):
        login_data = {
            "email": "test@example.com",
            "password": "testpassword"
        }
        login_response = client.post("/auth/login", json=login_data)
        assert login_response.status_code == 200
        
        token = login_response.json()["access_token"]
        
        headers = {"Authorization": f"Bearer {token}"}
        me_response = client.get("/auth/me", headers=headers)
        assert me_response.status_code == 200
        
        user_data = me_response.json()
        assert user_data["email"] == "test@example.com"
        assert user_data["name"] == "Test User"

    def test_token_required_for_protected_routes(self, client):
        response = client.get("/auth/me")
        assert response.status_code == 403