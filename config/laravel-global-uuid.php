<?php

return [
    /**
     * uuid model
     * This model is responsible for storing the global uuids. To add
     * extra behavior, you can change this to a class of your own. If
     * you choose to use a custom uuid model, it must extend the core
     * uuid model \Submtd\LaravelGlobalUuids\Models\GlobalUuid::class
     */
    'uuid_model' => \Submtd\LaravelGlobalUuids\Models\GlobalUuid::class,
];
