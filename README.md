# TasksBundle
Provides generic layer for task management

# Installation

```bash
composer require mpom/tasks-bundle
```

Add to your AppKernel.php:
```php
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new TasksBundle\TasksBundle,
            // ...
        )
    }
```

# Configuration
In your config.yml file add new options:
```yml
tasks:
    layout: AppBundle:Admin:Layout/custom.html.twig # optional, layout file
    entity_class: # your entity class
    template_list: # custom template for task list
    template_edit: # custom template for task edit
```

Add to app/config/routing.yml:
```yml
tasks:
    resource: "@TasksBundle/Resources/config/routing.yml"
    prefix:   /
```

Import database structure:
```bash
console doctrine:schema:update --force
```

Dump assets:
```bash
console assets:install --symlink
```

# Usage
Navigate to /<prefix>/tasks page.

```