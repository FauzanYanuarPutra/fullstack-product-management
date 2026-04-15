# Terraform Deployment Strategy

This test does not require real Terraform files, but the expected production infrastructure on DigitalOcean would be managed with Terraform.

## Resources to provision

- `digitalocean_project` to group the workload
- `digitalocean_vpc` for private networking
- `digitalocean_droplet` for application hosting
- `digitalocean_database_cluster` for managed PostgreSQL
- `digitalocean_firewall` to restrict public access
- `digitalocean_reserved_ip` for a stable public endpoint
- `digitalocean_domain` and `digitalocean_record` for DNS
- optional `digitalocean_container_registry` if the services move to container images
- optional `digitalocean_loadbalancer` if traffic grows beyond one droplet

## Suggested topology

- Nginx terminates HTTP/HTTPS and routes traffic by host.
- Nuxt is served on `app.example.com`.
- NestJS is served on `api.example.com`.
- Laravel Filament is served on `admin.example.com`.
- PostgreSQL runs on DigitalOcean Managed Databases to simplify backups and upgrades.

## Release flow

1. GitLab CI runs install, test, and build stages.
2. The deploy stage connects to the droplet over SSH or triggers a release worker.
3. The target host pulls the latest code or image.
4. Composer and npm production dependencies are installed.
5. Laravel migrations are run against PostgreSQL.
6. NestJS, PHP-FPM, and Nginx are reloaded.
7. Health endpoints are checked before the deployment is considered complete.

## Why this fits the brief

- It demonstrates infrastructure planning without overbuilding the take-home.
- It shows how CI/CD, networking, compute, and database resources would fit together.
- It keeps the explanation aligned with the requirement to describe Terraform usage without performing a real deployment.
