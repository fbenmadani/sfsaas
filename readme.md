

<p align="center">
<img src="https://img.shields.io/badge/PHP-8.3-informational?style=flat&logo=php&color=4f5b93" alt="Php Version">
<img src="https://img.shields.io/badge/Laravel-13-informational?style=flat&logo=laravel&color=4f5b93" alt="Laravel Version">
<img src="https://img.shields.io/badge/Livewire-4-informational?style=flat&logo=livewire&color=4f5b93" alt="Livewire Version">
<img src="https://img.shields.io/badge/MySQL-8.0-informational?style=flat&logo=mysql&color=4f5b93" alt="MySQL Version">
<img src="https://img.shields.io/badge/Tailwind-4-informational?style=flat&logo=tailwind&color=4f5b93" alt="Tailwind Version">


 
<a href="https://packagist.org/packages/fbenmadani/sfsaas"><img src="https://poser.pugx.org/fbenmadani/sfsaas/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/fbenmadani/sfsaas"><img src="https://poser.pugx.org/fbenmadani/sfsaas/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/fbenmadani/sfsaas"><img src="https://poser.pugx.org/fbenmadani/sfsaas/license.svg" alt="License"></a>
</p>


# SpeedForge SaaS Starter Kit

SpeedForge SaaS Kit is a production-ready starter for building multi-tenant SaaS applications. It ships with authentication, team management, subscription billing, and an admin dashboard out of the box.

Built on laravel 13, Livewire 4, Mysql, and Tailwind CSS 4.The Tall Stack  gives you a solid foundation to launch faster without sacrificing code quality or flexibility.

# Topics

1. [Introduction](#introduction)
2. [Features](#features)
3. [Roadmap](#roadmap)
4. [Installation](#installation)
5. [License](#license)

## Introduction

SpeedForge SaaS Starter Kit is a Laravel starter kit for building SaaS applications.

## Features
- Tall stack
- Multi-tenancy (Stancl/Tenancy)
- Livewire

## Roadmap


### Authentication
- Email and password login. Users can sign up and sign in with standard credentials, which is the baseline for most SaaS products.

- Social login. Boilerplates often include Google, GitHub, Facebook, or similar OAuth providers for faster onboarding.

- Magic link login. Passwordless sign-in through email links reduces friction and improves conversion.

- Password reset flow. Users can recover access securely without admin intervention.

- Email verification. New accounts confirm ownership of the email address before full access is granted.

- Two-factor authentication. An extra security layer protects accounts from unauthorized access.

- Multi-factor authentication. Some kits support stronger identity checks beyond basic passwords.

- Session management. Boilerplates typically handle login sessions, expiration, and refresh logic.

- Device/session list. Users can review active sessions and revoke suspicious ones.

- Rate limiting. Login throttling helps prevent brute-force attacks.

### User Management
User profiles. Users can store names, avatars, roles, and personal details.

- Profile editing. People can update account information from a self-service area.

- Account settings. Centralized pages let users manage security and personal preferences.

- Admin user list. Administrators can see all registered users in one place.

- User search. Teams can quickly find a user by name, email, or status.

- User impersonation. Support staff can temporarily act as a user for troubleshooting.

- User status control. Accounts can be activated, suspended, or disabled.

- User metadata fields. Boilerplates often support custom fields for product-specific needs.

- Last-login tracking. Admins can see when a user last accessed the app.

- User activity history. Important account actions can be logged for review.

- Team Management
Workspace creation. Users can create a shared organization or workspace.

- Team invitations. New members can join via invite links or email invitations.

- Member roles. Workspaces often support owner, admin, and member roles.

- Role-based access control. Permissions restrict what each user can view or change.

- Team switching. Users can move between multiple organizations or workspaces.

- Team member list. A dashboard shows everyone in the current workspace.

- Invite acceptance flow. Invitation links can be accepted or declined securely.

- Team removal. Admins can remove members from a workspace.

- Ownership transfer. Workspaces may allow changing the primary owner.

- Team activity log. Important changes inside the workspace are recorded.

### Billing
- Stripe integration. Stripe is one of the most common payment providers in SaaS boilerplates.

- Recurring subscriptions. The app supports monthly or yearly billing cycles.

- One-time payments. Some boilerplates support both subscriptions and single purchases.

- Pricing page. A prebuilt pricing page helps convert visitors into paying users.

- Checkout flow. Users can complete payment without custom payment logic.

- Customer portal. Subscribers can manage their own billing details and cards.

- Plan upgrades. Users can move from lower to higher plans.

- Plan downgrades. Users can reduce their plan when needed.

- Free trials. Trial periods can be configured for new customers.

- Coupons and discounts. Promotional pricing can be applied during checkout.

### Subscriptions
- Subscription status tracking. The app knows whether a plan is active, trialing, past due, or canceled.

- Subscription cancellation. Users can end recurring billing from the app or portal.

- Reactivation flow. Cancelled subscriptions can sometimes be restored easily.

- Billing cycle management. Renewal dates and cycles are tracked automatically.

- Subscription history. Past invoices and plan changes are stored for reference.

- Plan entitlements. Features can be enabled or disabled based on the current plan.

- Seat-based subscriptions. Pricing can depend on the number of users in a team.

- Organization-level subscriptions. Billing can be attached to a workspace instead of a single user.

- Subscription sync. Payment state is synchronized between the billing provider and the app.

- Grace periods. Some products allow limited access after payment failure.

- Payments and Invoicing
Payment method storage. Customers can save cards or payment methods for future charges.

- Invoice generation. The system can create receipts or invoices automatically.

- Tax handling. Some boilerplates include support for VAT or sales tax workflows.

- Billing address collection. Checkout can collect customer billing details.

- Payment retry logic. Failed charges can be retried automatically.

- Refund support. Admins may process refunds from the dashboard.

- Coupon redemption. Discount codes can be applied to purchases.

- Trial-to-paid conversion. Users can move from trial access into a paid plan.

- Payment webhooks. The app reacts to provider events like payment success or failure.

- Currency support. Products often support one or more billing currencies.

### Onboarding
- Welcome flow. New users are guided through initial setup.

- Product tour. Short walkthroughs show core features to first-time users.

- Setup checklist. A checklist helps users complete onboarding steps.

- Workspace creation wizard. Teams can create an organization in a structured flow.

- Empty-state guidance. Helpful prompts guide users when data is still empty.

- Goal selection. Users can choose use cases or preferences at signup.

- Sample data seeding. Demo content helps users understand the product faster.

-Invite team prompt. New users are encouraged to add teammates early.

- Onboarding progress tracking. The app can show how far setup has progressed.

- First-success milestone. The system celebrates the first key action completed.

### Dashboard and Analytics
- Admin dashboard. A central panel gives operators a view of the system.

-User dashboard. End users get a personalized home screen.

- Revenue metrics. SaaS kits often include billing and income summaries.

- Usage analytics. Products track activity and feature consumption.

- Subscription analytics. Dashboards can show churn, active plans, and conversions.

- KPI cards. Important metrics appear in clear summary tiles.

- Chart widgets. Boilerplates often ship with usage graphs and trend charts.

- Funnel tracking. Teams can monitor signup-to-paid conversion.

- Retention metrics. The app can show returning user behavior.

- Exportable reports. Data can be downloaded for analysis or accounting.

- Notifications and Email
Transactional emails. Apps send onboarding, billing, and security emails.

- Email templates. Standard messages are prebuilt and customizable.

- Verification emails. Account confirmation is usually handled automatically.

- Password reset emails. Recovery links are delivered by email.

- Billing emails. Payment receipts, renewal alerts, and failed-payment notices are included.

- Team invite emails. Invitation messages are sent to new members.

- In-app notifications. The interface can show alerts inside the product.

- Notification preferences. Users can choose which messages they receive.

- Scheduled digests. Some systems send daily or weekly summaries.

- Webhook notifications. External systems can be alerted about app events.

### Platform and Operations
- API foundation.The boilerplates an  include an API structure and documentation.

- Database setup. The data layer is preconfigured so you can start building fast.

- ORM integration. Many kits ship with a ready-to-use database abstraction layer.

- Multi-tenancy. Shared infrastructure can isolate data by company or workspace.

- Background jobs. Time-consuming tasks can run asynchronously.

- Logging. Apps often include event and error logs for debugging.

- Audit trails. Security-sensitive actions are recorded for compliance and support.

- Deployment support. Boilerplates will include production-ready hosting guidance both on Vps And counterner based hosting (docker & kubernetes ).

- Documentation. A good kit explains setup, customization, and usage.

- Modular architecture. Features are organized so teams can extend only what they need.


## Installation

- Clone the repository
  git clone git@github.com:fbenmadani/sfsaas.git

-  Switch to the repo folder
  cd sfsaas

- Install dependencies
  composer install

- Install frontend dependencies
  npm install

- Build frontend assets
  npm run build 

- Copy .env.example to .env
  cp .env.example .env

- Generate application key
  php artisan key:generate

-Run database migrations
  php artisan migrate

- Run database seeders
  php artisan db:seed

- Run the development server
  php artisan serve --host sfsaas.test --port 8000
  
## license
Speed Forge Saas kit (sfSaas) is a open-source starter kit for SaaS applications under the [MIT License](https://github.com/fbenmadani/sfsaas/blob/main/LICENSE).
### Security Vulnerabilities

Please don't disclose security vulnerabilities publicly. If you find any security vulnerability in sfSaas then please email us: f.benmadani[at]gmail.com.