<?php

use function Livewire\Volt\{layout, title};

layout('layouts.app');
title('Profile');

?>

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-neutral-800 shadow sm:rounded-box">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-neutral-800 shadow sm:rounded-box">
                <div class="max-w-xl">
                    <livewire:profile.update-password />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-neutral-800 shadow sm:rounded-box">
                <div class="max-w-xl">
                    <livewire:profile.delete-user />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
