def test_health_endpoint(client):
    resp = client.get("/health")
    assert resp.status_code == 200
    data = resp.json()
    assert isinstance(data, dict)
    assert data.get("status") == "ok"
    # timestamp should be a string (ISO format) after serialization
    assert isinstance(data.get("timestamp"), str)
    assert data.get("timestamp")