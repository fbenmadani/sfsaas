**Date:** 2026-03-31
**Status:** Draft
**Project:** SfSaas
**Version:** 1.0.0-MVP
**Author:** [Your Name]
**Description:** This document outlines the software requirements for the SfSaas project. 

## 1. Overview
### 1.1 Project Overview

This document outlines the functional and non-functional requirements for SfSaas, a cloud-based SaaS platform.

SfSaas is a complete SaaS starter kit that includes everything you need to start your SaaS business. It comes ready with a huge list of reusable components, an admin panel, user dashboard, user authentication, user & role management, plans & pricing, subscriptions, payments, emails, and more.
### 1.2 Scope
SfSaas provides a multi-tenant environment where merchants can:
- Mange Plans
- Manage pricing
- Manage Feaures

### 2. General Description
#### 2.1 Product Perspective
SfSaas is a Multi-Database Multi-Tenant application built on the TALL Stack (Tailwind, Alpine.js, Laravel, Livewire). It features a centralized management system for global administrative tasks and isolated databases for tenant-specific operational data.

#### 2.2 User Classes and Characteristics
- Global Admin (SaaS Manager): Manages plans, prices, and global MRR/ARR across DZD and USD.

- Tenant Admin (Merchant): Manages store settings, staff, and logistics providers.

- Store Staff: Handles daily order processing and tracking.

### 3. Functional Requirements
#### 3.1 Tenant Management & Infrastructure
- FR-1: System must provision a dedicated database for each new tenant upon registration.

- FR-2: Identification must be handled via subdomain (e.g., tenant.sfsaas.test).

FR-3: Tenant usage (Order count) must sync from tenant DB to central DB for billing limits.



#### 3.2 Tenant Administration 
- FR-4: Tenant Admin can create and manage users and roles.



### 4 External Interface Requirements
#### 4.1 User Interfaces
- Frontend: Responsive TALL Stack dashboard.

- Tenant UI: Mobile-first design for delivery tracking and receipt uploading.

- Admin UI: High-level metrics view (MRR per Currency).

#### 4.2 Software Interfaces
- Payment Gateways: Stripe (USD) and Manual Transfer/BaridiMob (DZD).

- PDF Engine: laravel-dompdf for document generation. 
### 5. Non-Functional Requirements
#### 5.1 Performance Requirements
- Database: PostgreSQL (Central) + MySQL (Tenant) for optimal query performance.

- Caching: Redis for caching tenant usage counts to reduce DB load.

#### 5.2 Security Requirements
- RBAC: Role-Based Access Control for tenant staff.

- Data Isolation: Strict separation of tenant data via database per tenant.

#### 5.3 Reliability Requirements
- Uptime: 99.9% SLA for SaaS operations.

- Backups: Daily automated backups for all tenant databases.