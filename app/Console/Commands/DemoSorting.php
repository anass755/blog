<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class DemoSorting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:sorting {column=name} {direction=asc}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Demonstrate the sorting functionality with sample data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $column = $this->argument('column');
        $direction = $this->argument('direction');

        $this->info("Demonstrating sorting by {$column} in {$direction} order...");
        $this->newLine();

        // Get users with sorting
        $users = User::sortBy($column, $direction)->get();

        if ($users->isEmpty()) {
            $this->warn('No users found. Please run php artisan db:seed first.');
            return;
        }

        // Display results in a table
        $headers = ['ID', 'Name', 'Email', 'Age', 'City', 'Created At'];
        $rows = [];

        foreach ($users as $user) {
            $rows[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->age,
                $user->city,
                $user->created_at->format('M d, Y'),
            ];
        }

        $this->table($headers, $rows);

        $this->newLine();
        $this->info("Total users: {$users->count()}");
        $this->info("Sorted by: {$column} ({$direction})");

        // Show available columns
        $this->newLine();
        $this->comment('Available sortable columns:');
        $sortableColumns = ['id', 'name', 'email', 'age', 'city', 'created_at'];
        foreach ($sortableColumns as $col) {
            $marker = $col === $column ? ' → ' : '   ';
            $this->line("{$marker}{$col}");
        }

        $this->newLine();
        $this->comment('Try: php artisan demo:sorting age desc');
        $this->comment('Or: php artisan demo:sorting city asc');
    }
}