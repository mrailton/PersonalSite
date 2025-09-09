from datetime import datetime, timezone

from app.models.cpc_item import CpcItem


class TestGetCPCItems:
    def test_returns_list_of_cpc_items_for_authenticated_user(self, client, test_db, auth_headers):
        existing_item_1 = CpcItem(
            item_type='Webinar',
            date=datetime.now(timezone.utc).date(),
            name='Test CPC Item',
            topics='Test CPC Item Topics',
            key_learning_outcomes='Test CPC Item Key Learning Outcomes',
            points=3,
            practice_change='Test CPC Item Practice Change',
        )

        existing_item_2 = CpcItem(
            item_type='Workshop',
            date=datetime.now(timezone.utc).date(),
            name='CPC Roadshow',
            topics='CPC Roadshow',
            key_learning_outcomes='CPC Roadshow',
            points=3,
            practice_change='No',
        )

        test_db.add(existing_item_1)
        test_db.add(existing_item_2)
        test_db.commit()

        response = client.get("/cpc", headers=auth_headers)

        assert response.status_code == 200
        data = response.json()
        assert len(data) == 2

    def test_requires_authentication(self, client):
        response = client.get("/cpc")
        assert response.status_code == 403

    def test_returns_empty_list_when_no_cpc_items(self, client, test_db, auth_headers):
        response = client.get("/cpc", headers=auth_headers)
        assert response.status_code == 200
        data = response.json()
        assert len(data) == 0


class TestGetCPCItem:
    def test_returns_single_cpc_item_for_authenticated_user(self, client, test_db, auth_headers):
        existing_item = CpcItem(
            item_type='Webinar',
            date=datetime.now(timezone.utc).date(),
            name='Test CPC Item',
            topics='Test CPC Item Topics',
            key_learning_outcomes='Test CPC Item Key Learning Outcomes',
            points=3,
            practice_change='Test CPC Item Practice Change',
        )

        test_db.add(existing_item)
        test_db.commit()
        test_db.refresh(existing_item)

        response = client.get(f"/cpc/{existing_item.id}", headers=auth_headers)

        assert response.status_code == 200
        data = response.json()
        assert data['id'] == existing_item.id
        assert data['name'] == existing_item.name
        assert data['topics'] == existing_item.topics
        assert data['key_learning_outcomes'] == existing_item.key_learning_outcomes
        assert data['practice_change'] == existing_item.practice_change

    def test_returns_404_when_cpc_item_not_found(self, client, auth_headers):
        response = client.get("/cpc/nonexistent-id", headers=auth_headers)
        assert response.status_code == 404
        assert response.json()['detail'] == "CPC item not found"

    def test_requires_authentication(self, client, test_db):
        existing_item = CpcItem(
            item_type='Webinar',
            date=datetime.now(timezone.utc).date(),
            name='Test CPC Item',
            topics='Test CPC Item Topics',
            key_learning_outcomes='Test CPC Item Key Learning Outcomes',
            points=3,
            practice_change='Test CPC Item Practice Change',
        )

        test_db.add(existing_item)
        test_db.commit()
        test_db.refresh(existing_item)

        response = client.get(f"/cpc/{existing_item.id}")
        assert response.status_code == 403


class TestCreateCPCItem:
    def test_creates_cpc_item_successfully(self, client, test_db, auth_headers):
        cpc_data = {
            "name": "New CPC Item",
            "item_type": "Conference",
            "date": "2024-01-01",
            "topics": "Learning and Development",
            "key_learning_outcomes": "Improved understanding of best practices",
            "points": 5,
            "practice_change": "Will implement new strategies"
        }

        response = client.post("/cpc", json=cpc_data, headers=auth_headers)

        assert response.status_code == 201
        data = response.json()
        assert data['name'] == cpc_data['name']
        assert data['item_type'] == cpc_data['item_type']
        assert data['topics'] == cpc_data['topics']
        assert data['key_learning_outcomes'] == cpc_data['key_learning_outcomes']
        assert data['points'] == cpc_data['points']
        assert data['practice_change'] == cpc_data['practice_change']
        assert 'id' in data
        assert 'created_at' in data
        assert 'updated_at' in data

        created_item = test_db.query(CpcItem).filter(CpcItem.id == data['id']).first()
        assert created_item is not None
        assert created_item.name == cpc_data['name']

    def test_requires_authentication(self, client):
        cpc_data = {
            "name": "New CPC Item",
            "item_type": "Conference",
            "date": "2024-01-01",
            "topics": "Learning and Development",
            "key_learning_outcomes": "Improved understanding of best practices",
            "points": 5,
            "practice_change": "Will implement new strategies"
        }

        response = client.post("/cpc", json=cpc_data)
        assert response.status_code == 403

    def test_validates_required_fields(self, client, auth_headers):
        cpc_data = {
            "name": "New CPC Item"
        }

        response = client.post("/cpc", json=cpc_data, headers=auth_headers)
        assert response.status_code == 422

    def test_validates_field_constraints(self, client, auth_headers):
        cpc_data = {
            "name": "",  # Empty name should fail
            "item_type": "Conference",
            "date": "2024-01-01",
            "topics": "Learning and Development",
            "key_learning_outcomes": "Improved understanding of best practices",
            "points": -1,  # Negative points should fail
            "practice_change": "Will implement new strategies"
        }

        response = client.post("/cpc", json=cpc_data, headers=auth_headers)
        assert response.status_code == 422


class TestUpdateCPCItem:
    def test_updates_cpc_item_successfully(self, client, test_db, auth_headers):
        existing_item = CpcItem(
            item_type='Webinar',
            date=datetime.now(timezone.utc).date(),
            name='Original Name',
            topics='Original Topics',
            key_learning_outcomes='Original Learning Outcomes',
            points=3,
            practice_change='Original Practice Change',
        )

        test_db.add(existing_item)
        test_db.commit()
        test_db.refresh(existing_item)

        update_data = {
            "name": "Updated Name",
            "points": 5
        }

        response = client.put(f"/cpc/{existing_item.id}", json=update_data, headers=auth_headers)

        assert response.status_code == 200
        data = response.json()
        assert data['name'] == update_data['name']
        assert data['points'] == update_data['points']
        assert data['topics'] == existing_item.topics  # Unchanged
        assert data['id'] == existing_item.id

        updated_item = test_db.query(CpcItem).filter(CpcItem.id == existing_item.id).first()
        assert updated_item.name == update_data['name']
        assert updated_item.points == update_data['points']

    def test_partial_update_works(self, client, test_db, auth_headers):
        existing_item = CpcItem(
            item_type='Webinar',
            date=datetime.now(timezone.utc).date(),
            name='Original Name',
            topics='Original Topics',
            key_learning_outcomes='Original Learning Outcomes',
            points=3,
            practice_change='Original Practice Change',
        )

        test_db.add(existing_item)
        test_db.commit()
        test_db.refresh(existing_item)

        update_data = {"name": "Only Name Updated"}

        response = client.put(f"/cpc/{existing_item.id}", json=update_data, headers=auth_headers)

        assert response.status_code == 200
        data = response.json()
        assert data['name'] == update_data['name']
        assert data['points'] == existing_item.points  # Unchanged

    def test_returns_404_when_cpc_item_not_found(self, client, auth_headers):
        update_data = {"name": "Updated Name"}
        response = client.put("/cpc/nonexistent-id", json=update_data, headers=auth_headers)
        assert response.status_code == 404
        assert response.json()['detail'] == "CPC item not found"

    def test_requires_authentication(self, client, test_db):
        existing_item = CpcItem(
            item_type='Webinar',
            date=datetime.now(timezone.utc).date(),
            name='Original Name',
            topics='Original Topics',
            key_learning_outcomes='Original Learning Outcomes',
            points=3,
            practice_change='Original Practice Change',
        )

        test_db.add(existing_item)
        test_db.commit()
        test_db.refresh(existing_item)

        update_data = {"name": "Updated Name"}
        response = client.put(f"/cpc/{existing_item.id}", json=update_data)
        assert response.status_code == 403

    def test_validates_field_constraints_on_update(self, client, test_db, auth_headers):
        existing_item = CpcItem(
            item_type='Webinar',
            date=datetime.now(timezone.utc).date(),
            name='Original Name',
            topics='Original Topics',
            key_learning_outcomes='Original Learning Outcomes',
            points=3,
            practice_change='Original Practice Change',
        )

        test_db.add(existing_item)
        test_db.commit()
        test_db.refresh(existing_item)

        update_data = {"points": -1}  # Invalid negative points

        response = client.put(f"/cpc/{existing_item.id}", json=update_data, headers=auth_headers)
        assert response.status_code == 422


class TestDeleteCPCItem:
    def test_deletes_cpc_item_successfully(self, client, test_db, auth_headers):
        existing_item = CpcItem(
            item_type='Webinar',
            date=datetime.now(timezone.utc).date(),
            name='Item to Delete',
            topics='Topics',
            key_learning_outcomes='Learning Outcomes',
            points=3,
            practice_change='Practice Change',
        )

        test_db.add(existing_item)
        test_db.commit()
        test_db.refresh(existing_item)

        response = client.delete(f"/cpc/{existing_item.id}", headers=auth_headers)

        assert response.status_code == 204
        assert response.content == b''

        deleted_item = test_db.query(CpcItem).filter(CpcItem.id == existing_item.id).first()
        assert deleted_item is None

    def test_returns_404_when_cpc_item_not_found(self, client, auth_headers):
        response = client.delete("/cpc/nonexistent-id", headers=auth_headers)
        assert response.status_code == 404
        assert response.json()['detail'] == "CPC item not found"

    def test_requires_authentication(self, client, test_db):
        existing_item = CpcItem(
            item_type='Webinar',
            date=datetime.now(timezone.utc).date(),
            name='Item to Delete',
            topics='Topics',
            key_learning_outcomes='Learning Outcomes',
            points=3,
            practice_change='Practice Change',
        )

        test_db.add(existing_item)
        test_db.commit()
        test_db.refresh(existing_item)

        response = client.delete(f"/cpc/{existing_item.id}")
        assert response.status_code == 403