<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $feature_projects = Feature::create([
            'name' => 'Projects',
            'slug' => 'projects',
            'type' => 'limit',
            // 'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Tasks',
            'slug' => 'tasks',
            'type' => 'limit',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Users',
            'slug' => 'users',
            'type' => 'limit',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Storage',
            'slug' => 'storage',
            'type' => 'limit',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Support',
            'slug' => 'support',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Domain',
            'slug' => 'custom_domain',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'API Access',
            'slug' => 'api_access',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'White Label',
            'slug' => 'white_label',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'SSO',
            'slug' => 'sso',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Audit Logs',
            'slug' => 'audit_logs',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'SLA',
            'slug' => 'sla',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Dedicated Support',
            'slug' => 'dedicated_support',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Onboarding',
            'slug' => 'custom_onboarding',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Features',
            'slug' => 'custom_features',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Branding',
            'slug' => 'custom_branding',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Integrations',
            'slug' => 'custom_integrations',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Reports',
            'slug' => 'custom_reports',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Dashboards',
            'slug' => 'custom_dashboards',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Workflows',
            'slug' => 'custom_workflows',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Fields',
            'slug' => 'custom_fields',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Notifications',
            'slug' => 'custom_notifications',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Permissions',
            'slug' => 'custom_permissions',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Roles',
            'slug' => 'custom_roles',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Templates',
            'slug' => 'custom_templates',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Themes',
            'slug' => 'custom_themes',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Colors',
            'slug' => 'custom_colors',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Fonts',
            'slug' => 'custom_fonts',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Layouts',
            'slug' => 'custom_layouts',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Modules',
            'slug' => 'custom_modules',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Components',
            'slug' => 'custom_components',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Pages',
            'slug' => 'custom_pages',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Menus',
            'slug' => 'custom_menus',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Widgets',
            'slug' => 'custom_widgets',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Reports',
            'slug' => 'custom_reports',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Dashboards',
            'slug' => 'custom_dashboards',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Workflows',
            'slug' => 'custom_workflows',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Fields',
            'slug' => 'custom_fields',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Notifications',
            'slug' => 'custom_notifications',
            'type' => 'boolean',
            'is_active' => true,
        ]);

        Feature::create([
            'name' => 'Custom Permissions',
            'slug' => 'custom_permissions',
            'type' => 'boolean',
            'is_active' => true,
        ]);

    }
}
