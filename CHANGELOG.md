# Changelog

All notable changes to the One Love Proposal App will be documented in this file.

## [1.1.0] - 2023-06-20

### Added
- Enhanced notification system to filter proposals by selected user
- Added JavaScript functionality to dynamically update proposal options based on selected user
- Improved proposal review workflow with better UI feedback

### Fixed
- Fixed route definitions for admin modules (clients, documents, notifications)
- Resolved database migration conflicts for templates and tasks tables
- Fixed "Undefined variable $clients" error in notification creation
- Fixed "Column not found: 1054 Unknown column 'title' in field list" error in notifications
- Fixed budget constraint notification system
- Fixed proposal review page routing

### Changed
- Updated database migrations to check for table existence before creation
- Improved error handling in notification creation process
- Enhanced proposal selection in notification creation form

## [1.0.0] - 2023-06-01

### Added
- Initial release of the One Love Proposal App
- Core modules: Proposals, Clients, Templates, Tasks, Documents, Reports, Notifications
- User management with admin and regular user roles
- Dashboard with key metrics and analytics
- Proposal creation, editing, and management
- Client information management
- Template system for reusable proposals
- Task management with due dates and priorities
- Document upload and management
- Reporting and analytics features
- Notification system for important events