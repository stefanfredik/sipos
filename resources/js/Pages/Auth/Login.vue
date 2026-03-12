<script setup lang="ts">
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Checkbox } from '@/components/ui/checkbox';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <AuthLayout title="Log in">
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-4">
            <div class="space-y-2">
                <Label for="username">Username</Label>
                <Input
                    id="username"
                    type="text"
                    v-model="form.username"
                    required
                    autofocus
                    autocomplete="username"
                    :class="{ 'border-destructive': form.errors.username }"
                />
                <p v-if="form.errors.username" class="text-sm text-destructive">
                    {{ form.errors.username }}
                </p>
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <Label for="password">Password</Label>
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm font-medium text-primary hover:underline"
                    >
                        Lupa password?
                    </Link>
                </div>
                <Input
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    :class="{ 'border-destructive': form.errors.password }"
                />
                <p v-if="form.errors.password" class="text-sm text-destructive">
                    {{ form.errors.password }}
                </p>
            </div>

            <div class="flex items-center space-x-2">
                <Checkbox id="remember" v-model:checked="form.remember" />
                <Label
                    for="remember"
                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                >
                    Ingat saya
                </Label>
            </div>

            <Button
                type="submit"
                class="w-full"
                :disabled="form.processing"
            >
                <span v-if="form.processing">Logging in...</span>
                <span v-else>Log in</span>
            </Button>
        </form>
    </AuthLayout>
</template>
