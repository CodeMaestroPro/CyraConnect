<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            'Programming' => ['PHP', 'JavaScript', 'Python', 'Java', 'TypeScript', 'C#', 'Go', 'Rust', 'Swift', 'Kotlin'],
            'Frontend' => ['HTML/CSS', 'React', 'Vue.js', 'Tailwind CSS', 'Next.js', 'Angular'],
            'Backend' => ['Laravel', 'Node.js', 'Django', 'Spring Boot', 'Express.js', 'REST APIs', 'GraphQL'],
            'Database' => ['MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'SQLite'],
            'DevOps' => ['Docker', 'Kubernetes', 'AWS', 'CI/CD', 'Linux', 'Nginx'],
            'Design' => ['UI/UX Design', 'Figma', 'Adobe XD', 'Graphic Design', 'Prototyping'],
            'Business' => ['Project Management', 'Agile/Scrum', 'Business Analysis', 'Product Management', 'Marketing'],
            'Data' => ['Data Analysis', 'Machine Learning', 'SQL', 'Power BI', 'Excel'],
        ];

        foreach ($skills as $category => $names) {
            foreach ($names as $name) {
                Skill::updateOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name, 'category' => $category, 'is_active' => true]
                );
            }
        }
    }
}
