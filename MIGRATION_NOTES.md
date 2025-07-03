# Migration Notes

This document provides important information about database migrations in the One Love Proposal App.

## Known Migration Issues

### Duplicate Table Migrations

The project contains some duplicate migration files that attempt to create the same tables:

1. **Templates Table**:
   - `2023_06_16_000000_create_templates_table.php`
   - `2025_06_15_000001_create_templates_table.php`

2. **Tasks Table**:
   - `2023_06_17_000000_create_tasks_table.php`
   - `2025_06_15_000002_create_tasks_table.php`

3. **Notifications Table**:
   - `2023_06_19_000000_create_notifications_table.php`
   - `2025_06_10_081710_create_notifications_table.php`

### Resolution

These issues have been resolved by:

1. Adding checks to verify if tables exist before attempting to create them:
   ```php
   if (!Schema::hasTable('table_name')) {
       Schema::create('table_name', function (Blueprint $table) {
           // table definition
       });
   }
   ```

2. Modifying the `down()` methods to prevent dropping tables that might be needed by other migrations:
   ```php
   public function down(): void
   {
       // Don't drop the table in down method to prevent conflicts
   }
   ```

3. Creating a new migration (`2025_06_20_000000_update_notifications_table.php`) to ensure the notifications table has the correct structure.

## Running Migrations

When running migrations, use the standard Laravel command:

```
php artisan migrate
```

If you encounter any issues, you can try:

```
php artisan migrate:fresh --seed
```

**Warning**: This will drop all tables and recreate them, so only use in development environments or when you're sure you want to reset all data.

## Database Structure

The current database structure includes the following main tables:

- `users`: User accounts and authentication
- `proposals`: Core proposal data
- `clients`: Client information
- `templates`: Reusable proposal templates
- `tasks`: Task management
- `documents`: Document storage and metadata
- `notifications`: System notifications
- `project_categories`: Categorization for proposals and templates
- `proposal_feedbacks`: Feedback on proposals
- `proposal_versions`: Version history for proposals

## Future Migration Considerations

When adding new migrations, consider:

1. Using unique timestamps to avoid conflicts
2. Adding checks for table existence before creation
3. Using `Schema::hasColumn()` when adding columns to existing tables
4. Being cautious with `down()` methods to prevent data loss