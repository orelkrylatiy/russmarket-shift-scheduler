# Laravel API

The production API is a Laravel application running on the VPS at
`/russmarket-api`. It exposes schedule reads and shift create/update/delete
operations backed by SQLite. The full Laravel skeleton is provisioned on the
server from the same routes and migration sources in this folder.

Endpoints:

- `GET /api/health`
- `GET /api/schedule?week=YYYY-MM-DD`
- `POST /api/shifts`
- `PUT /api/shifts/{id}`
- `DELETE /api/shifts/{id}`
