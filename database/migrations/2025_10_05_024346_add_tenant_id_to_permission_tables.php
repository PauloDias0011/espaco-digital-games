<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableNames = config('permission.table_names');

        // Adicionar tenant_id na tabela roles
        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->onDelete('cascade');
            $table->index(['tenant_id', 'name', 'guard_name'], 'roles_tenant_name_guard_index');
        });

        // Adicionar tenant_id na tabela permissions
        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->onDelete('cascade');
            $table->index(['tenant_id', 'name', 'guard_name'], 'permissions_tenant_name_guard_index');
        });

        // Adicionar tenant_id na tabela model_has_roles
        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->onDelete('cascade');
            $table->index(['tenant_id', 'role_id'], 'model_has_roles_tenant_role_index');
        });

        // Adicionar tenant_id na tabela model_has_permissions
        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->onDelete('cascade');
            $table->index(['tenant_id', 'permission_id'], 'model_has_permissions_tenant_permission_index');
        });

        // Adicionar tenant_id na tabela role_has_permissions
        Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->constrained('tenants')->onDelete('cascade');
            $table->index(['tenant_id', 'permission_id', 'role_id'], 'role_has_permissions_tenant_permission_role_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tableNames = config('permission.table_names');

        // Remover índices e colunas tenant_id
        Schema::table($tableNames['roles'], function (Blueprint $table) {
            $table->dropIndex('roles_tenant_name_guard_index');
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::table($tableNames['permissions'], function (Blueprint $table) {
            $table->dropIndex('permissions_tenant_name_guard_index');
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::table($tableNames['model_has_roles'], function (Blueprint $table) {
            $table->dropIndex('model_has_roles_tenant_role_index');
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::table($tableNames['model_has_permissions'], function (Blueprint $table) {
            $table->dropIndex('model_has_permissions_tenant_permission_index');
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::table($tableNames['role_has_permissions'], function (Blueprint $table) {
            $table->dropIndex('role_has_permissions_tenant_permission_role_index');
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });
    }
};