<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\{ICategory, IComment, IPost, IProject, ISkill, ITraining, IUser};
use App\Repositories\Eloquent\{CategoryRepository,
    CommentRepository,
    PostRepository,
    ProjectRepository,
    SkillRepository,
    TrainingRepository,
    UserRepository};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(IUser::class, UserRepository::class);
        $this->app->bind(ITraining::class, TrainingRepository::class);
        $this->app->bind(ISkill::class, SkillRepository::class);
        $this->app->bind(IProject::class, ProjectRepository::class);
        $this->app->bind(IPost::class, PostRepository::class);
        $this->app->bind(ICategory::class, CategoryRepository::class);
        $this->app->bind(IComment::class, CommentRepository::class);

    }
}
