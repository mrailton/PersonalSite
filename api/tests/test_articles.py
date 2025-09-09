from datetime import datetime, timedelta, timezone

import pytest

from app.models.article import Article


class TestGetArticles:

    def test_returns_published_only_for_guest(self, client, test_db):
        now = datetime.now(timezone.utc)
        published_article_1 = Article(
            title="Published Article 1",
            slug="published-article-1",
            content="Content of published article 1",
            published_at=now - timedelta(days=2)
        )
        published_article_2 = Article(
            title="Published Article 2",
            slug="published-article-2",
            content="Content of published article 2",
            published_at=now - timedelta(days=1)
        )
        future_article = Article(
            title="Future Article",
            slug="future-article",
            content="Content of future article",
            published_at=now + timedelta(days=1)
        )
        draft_article = Article(
            title="Draft Article",
            slug="draft-article",
            content="Content of draft article",
            published_at=None
        )
        
        test_db.add_all([published_article_1, published_article_2, future_article, draft_article])
        test_db.commit()
        
        response = client.get("/articles")
        
        assert response.status_code == 200
        data = response.json()
        assert len(data) == 2
        assert all(article["title"].startswith("Published") for article in data)

    def test_ordered_by_published_date_desc(self, client, test_db):
        now = datetime.now(timezone.utc)
        published_article_1 = Article(
            title="Published Article 1",
            slug="published-article-1",
            content="Content of published article 1",
            published_at=now - timedelta(days=2)
        )
        published_article_2 = Article(
            title="Published Article 2",
            slug="published-article-2",
            content="Content of published article 2",
            published_at=now - timedelta(days=1)
        )
        
        test_db.add_all([published_article_1, published_article_2])
        test_db.commit()
        
        response = client.get("/articles")
        
        assert response.status_code == 200
        data = response.json()
        assert len(data) == 2
        assert data[0]["title"] == "Published Article 2"  # More recent first
        assert data[1]["title"] == "Published Article 1"

    def test_with_limit_parameter(self, client, test_db):
        now = datetime.now(timezone.utc)
        published_article_1 = Article(
            title="Published Article 1",
            slug="published-article-1",
            content="Content of published article 1",
            published_at=now - timedelta(days=2)
        )
        published_article_2 = Article(
            title="Published Article 2",
            slug="published-article-2",
            content="Content of published article 2",
            published_at=now - timedelta(days=1)
        )
        
        test_db.add_all([published_article_1, published_article_2])
        test_db.commit()
        
        response = client.get("/articles?limit=1")
        
        assert response.status_code == 200
        data = response.json()
        assert len(data) == 1
        assert data[0]["title"] == "Published Article 2"  # Most recent

    def test_empty_result(self, client, test_db):
        response = client.get("/articles")
        
        assert response.status_code == 200
        data = response.json()
        assert data == []

    def test_invalid_limit_zero(self, client):
        response = client.get("/articles?limit=0")
        assert response.status_code == 422

    def test_invalid_limit_negative(self, client):
        response = client.get("/articles?limit=-1")
        assert response.status_code == 422

    def test_response_schema_excludes_content(self, client, test_db):
        now = datetime.now(timezone.utc)
        published_article = Article(
            title="Published Article",
            slug="published-article",
            content="This content should not be in the response",
            published_at=now - timedelta(days=1)
        )
        
        test_db.add(published_article)
        test_db.commit()
        
        response = client.get("/articles")
        
        assert response.status_code == 200
        data = response.json()
        assert len(data) == 1
        
        article = data[0]
        required_fields = ["id", "title", "slug", "published_at", "created_at", "updated_at"]
        for field in required_fields:
            assert field in article
        
        assert "content" not in article

    def test_authenticated_user_sees_all_articles(self, client, test_db, auth_headers):
        now = datetime.now(timezone.utc)
        published_article = Article(
            title="Published Article",
            slug="published-article",
            content="Published content",
            published_at=now - timedelta(days=1)
        )
        future_article = Article(
            title="Future Article",
            slug="future-article",
            content="Future content",
            published_at=now + timedelta(days=1)
        )
        draft_article = Article(
            title="Draft Article",
            slug="draft-article",
            content="Draft content",
            published_at=None
        )
        
        test_db.add_all([published_article, future_article, draft_article])
        test_db.commit()
        
        response = client.get("/articles", headers=auth_headers)
        
        assert response.status_code == 200
        data = response.json()
        assert len(data) == 3  # All articles visible to authenticated user


class TestGetArticleBySlug:

    def test_success(self, client, test_db):
        now = datetime.now(timezone.utc)
        published_article = Article(
            title="Published Article 1",
            slug="published-article-1",
            content="Content of published article 1",
            published_at=now - timedelta(days=1)
        )
        
        test_db.add(published_article)
        test_db.commit()
        
        response = client.get("/articles/published-article-1")
        
        assert response.status_code == 200
        data = response.json()
        assert data["slug"] == "published-article-1"
        assert data["title"] == "Published Article 1"
        assert "content" in data

    def test_not_found(self, client, test_db):
        response = client.get("/articles/nonexistent-slug")
        
        assert response.status_code == 404
        data = response.json()
        assert data["detail"] == "Article not found"

    def test_future_article_not_accessible(self, client, test_db):
        now = datetime.now(timezone.utc)
        future_article = Article(
            title="Future Article",
            slug="future-article",
            content="Content of future article",
            published_at=now + timedelta(days=1)
        )
        
        test_db.add(future_article)
        test_db.commit()
        
        response = client.get("/articles/future-article")
        
        assert response.status_code == 404
        data = response.json()
        assert data["detail"] == "Article not found"

    def test_draft_article_not_accessible(self, client, test_db):
        draft_article = Article(
            title="Draft Article",
            slug="draft-article",
            content="Content of draft article",
            published_at=None
        )
        
        test_db.add(draft_article)
        test_db.commit()
        
        response = client.get("/articles/draft-article")
        
        assert response.status_code == 404
        data = response.json()
        assert data["detail"] == "Article not found"

    def test_authenticated_user_can_access_unpublished(self, client, test_db, auth_headers):
        now = datetime.now(timezone.utc)
        future_article = Article(
            title="Future Article",
            slug="future-article",
            content="Content of future article",
            published_at=now + timedelta(days=1)
        )
        
        test_db.add(future_article)
        test_db.commit()
        
        response = client.get("/articles/future-article", headers=auth_headers)
        
        assert response.status_code == 200
        data = response.json()
        assert data["slug"] == "future-article"
        assert data["title"] == "Future Article"

    def test_response_schema_includes_content(self, client, test_db):
        now = datetime.now(timezone.utc)
        published_article = Article(
            title="Published Article",
            slug="published-article",
            content="This is the full content",
            published_at=now - timedelta(days=1)
        )
        
        test_db.add(published_article)
        test_db.commit()
        
        response = client.get("/articles/published-article")
        
        assert response.status_code == 200
        data = response.json()
        
        required_fields = ["id", "title", "slug", "content", "published_at", "created_at", "updated_at"]
        for field in required_fields:
            assert field in data


class TestCreateArticle:

    def test_success(self, client, test_user, auth_headers):
        article_data = {
            "title": "New Test Article",
            "slug": "new-test-article",
            "content": "This is the content of the new test article",
            "published_at": None
        }
        
        response = client.post("/articles", json=article_data, headers=auth_headers)
        
        assert response.status_code == 201
        data = response.json()
        assert data["title"] == "New Test Article"
        assert data["slug"] == "new-test-article"
        assert data["content"] == "This is the content of the new test article"
        assert data["published_at"] is None
        assert "id" in data
        assert "created_at" in data
        assert "updated_at" in data

    def test_unauthorized(self, client):
        article_data = {
            "title": "New Test Article",
            "slug": "new-test-article",
            "content": "This is the content of the new test article"
        }
        
        response = client.post("/articles", json=article_data)
        
        assert response.status_code == 403

    def test_missing_required_fields(self, client, auth_headers):
        response = client.post("/articles", json={"title": "Incomplete Article"}, headers=auth_headers)
        assert response.status_code == 422
        
        response = client.post("/articles", json={"content": "Just content"}, headers=auth_headers)
        assert response.status_code == 422

    def test_duplicate_slug(self, client, auth_headers, test_db):
        from app.models.article import Article
        
        existing_article = Article(
            title="Existing Article",
            slug="test-article",
            content="Existing content"
        )
        test_db.add(existing_article)
        test_db.commit()
        
        article_data = {
            "title": "New Article",
            "slug": "test-article",
            "content": "New content"
        }
        
        response = client.post("/articles", json=article_data, headers=auth_headers)
        assert response.status_code == 400
        assert "slug already exists" in response.json()["detail"].lower()

    def test_with_published_at(self, client, auth_headers):
        from datetime import datetime, timezone
        
        published_at = datetime.now(timezone.utc).isoformat()
        article_data = {
            "title": "Published Article",
            "slug": "published-article",
            "content": "This article is published",
            "published_at": published_at
        }
        
        response = client.post("/articles", json=article_data, headers=auth_headers)
        
        assert response.status_code == 201
        data = response.json()
        assert data["published_at"] is not None

    def test_empty_title(self, client, auth_headers):
        article_data = {
            "title": "",
            "slug": "empty-title",
            "content": "Content"
        }
        
        response = client.post("/articles", json=article_data, headers=auth_headers)
        assert response.status_code == 422

    def test_empty_content(self, client, auth_headers):
        article_data = {
            "title": "Title",
            "slug": "empty-content",
            "content": ""
        }
        
        response = client.post("/articles", json=article_data, headers=auth_headers)
        assert response.status_code == 422


class TestUpdateArticle:

    def test_success(self, client, test_db, auth_headers):
        from app.models.article import Article
        
        existing_article = Article(
            title="Original Title",
            slug="original-slug",
            content="Original content"
        )
        test_db.add(existing_article)
        test_db.commit()
        test_db.refresh(existing_article)
        
        update_data = {
            "title": "Updated Title",
            "content": "Updated content",
            "published_at": None
        }
        
        response = client.put(f"/articles/{existing_article.id}", json=update_data, headers=auth_headers)
        
        assert response.status_code == 200
        data = response.json()
        assert data["title"] == "Updated Title"
        assert data["content"] == "Updated content"
        assert data["slug"] == "original-slug"  # slug should not change
        assert data["published_at"] is None
        assert data["id"] == existing_article.id

    def test_unauthorized(self, client, test_db):
        from app.models.article import Article
        
        existing_article = Article(
            title="Original Title",
            slug="original-slug",
            content="Original content"
        )
        test_db.add(existing_article)
        test_db.commit()
        
        update_data = {
            "title": "Updated Title",
            "content": "Updated content"
        }
        
        response = client.put(f"/articles/{existing_article.id}", json=update_data)
        assert response.status_code == 403

    def test_article_not_found(self, client, auth_headers):
        update_data = {
            "title": "Updated Title",
            "content": "Updated content"
        }
        
        response = client.put("/articles/nonexistent-id", json=update_data, headers=auth_headers)
        assert response.status_code == 404
        assert "Article not found" in response.json()["detail"]

    def test_partial_update_allowed(self, client, test_db, auth_headers):
        from app.models.article import Article
        
        existing_article = Article(
            title="Original Title",
            slug="original-slug",
            content="Original content"
        )
        test_db.add(existing_article)
        test_db.commit()
        test_db.refresh(existing_article)
        
        response = client.put(f"/articles/{existing_article.id}", json={"title": "Updated Title Only"}, headers=auth_headers)
        
        assert response.status_code == 200
        data = response.json()
        assert data["title"] == "Updated Title Only"
        assert data["content"] == "Original content"  # Should preserve original content
        assert data["slug"] == "original-slug"

    def test_empty_fields(self, client, test_db, auth_headers):
        from app.models.article import Article
        
        existing_article = Article(
            title="Original Title",
            slug="original-slug",
            content="Original content"
        )
        test_db.add(existing_article)
        test_db.commit()
        
        update_data = {
            "title": "",
            "content": "Updated content"
        }
        
        response = client.put(f"/articles/{existing_article.id}", json=update_data, headers=auth_headers)
        assert response.status_code == 422

    def test_partial_update_preserves_existing_values(self, client, test_db, auth_headers):
        from app.models.article import Article
        from datetime import datetime, timezone
        
        published_date = datetime.now(timezone.utc)
        existing_article = Article(
            title="Original Title",
            slug="original-slug",
            content="Original content",
            published_at=published_date
        )
        test_db.add(existing_article)
        test_db.commit()
        test_db.refresh(existing_article)
        
        update_data = {
            "title": "Updated Title",
            "content": "Updated content"
        }
        
        response = client.put(f"/articles/{existing_article.id}", json=update_data, headers=auth_headers)
        
        assert response.status_code == 200
        data = response.json()
        assert data["title"] == "Updated Title"
        assert data["content"] == "Updated content"
        assert data["slug"] == "original-slug"
        assert data["published_at"] is not None  # Should preserve original published_at


class TestDeleteArticle:

    def test_success(self, client, test_db, auth_headers):
        from app.models.article import Article
        
        existing_article = Article(
            title="Article to Delete",
            slug="article-to-delete",
            content="This article will be deleted"
        )
        test_db.add(existing_article)
        test_db.commit()
        test_db.refresh(existing_article)
        
        response = client.delete(f"/articles/{existing_article.id}", headers=auth_headers)
        
        assert response.status_code == 204
        
        # Verify article is actually deleted from database
        deleted_article = test_db.query(Article).filter(Article.id == existing_article.id).first()
        assert deleted_article is None

    def test_unauthorized(self, client, test_db):
        from app.models.article import Article
        
        existing_article = Article(
            title="Article to Delete",
            slug="article-to-delete",
            content="This article will be deleted"
        )
        test_db.add(existing_article)
        test_db.commit()
        
        response = client.delete(f"/articles/{existing_article.id}")
        assert response.status_code == 403

    def test_article_not_found(self, client, auth_headers):
        response = client.delete("/articles/nonexistent-id", headers=auth_headers)
        assert response.status_code == 404
        assert "Article not found" in response.json()["detail"]

    def test_delete_idempotent(self, client, test_db, auth_headers):
        from app.models.article import Article
        
        existing_article = Article(
            title="Article to Delete",
            slug="article-to-delete",
            content="This article will be deleted"
        )
        test_db.add(existing_article)
        test_db.commit()
        article_id = existing_article.id
        
        # First delete should succeed
        response = client.delete(f"/articles/{article_id}", headers=auth_headers)
        assert response.status_code == 204
        
        # Second delete should return 404 (not found)
        response = client.delete(f"/articles/{article_id}", headers=auth_headers)
        assert response.status_code == 404