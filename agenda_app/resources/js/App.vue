<template>
    <div class="h-screen flex p-4 md:p-6 gap-6 overflow-hidden">
        <AppSidebar :active-view="activeView" @navigate="activeView = $event" @logout="logout" />

        <AgendaDashboard
            v-if="activeView === 'agenda'"
            :user="sidebarUser"
            :initial-view="profile.preferred_view || 'month'"
        />

        <ProfileView
            v-else
            :profile="profile"
            @updated="onProfileUpdated"
        />
    </div>
</template>

<script setup>
import axios from 'axios'
import { computed, reactive, ref } from 'vue'
import AgendaDashboard from './components/AgendaDashboard.vue'
import AppSidebar from './components/AppSidebar.vue'
import ProfileView from './components/ProfileView.vue'

const activeView = ref('agenda')

const profile = reactive({
    name: 'Utente',
    email: '',
    plan: 'Free Plan',
    avatar_url: 'https://i.pravatar.cc/160?img=59',
    timezone: 'Europe/Rome',
    preferred_view: 'month',
    bio: '',
})

const sidebarUser = computed(() => ({
    name: profile.name,
    plan: profile.plan,
    avatar: profile.avatar_url || 'https://i.pravatar.cc/160?img=59',
}))

function onProfileUpdated(data) {
    Object.assign(profile, data)
}

async function loadProfile() {
    const { data } = await axios.get('/api/profile')
    Object.assign(profile, data.profile)
}

async function logout() {
    await axios.post('/logout')
    window.location.href = '/login'
}

loadProfile()
</script>
