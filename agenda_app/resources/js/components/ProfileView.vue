<template>
    <div class="flex-1 bg-[#0d120d] rounded-[3rem] border border-white/5 p-6 md:p-8 overflow-y-auto custom-scrollbar">
        <h1 class="text-2xl font-black mb-6">Configurazione Profilo</h1>

        <div class="mb-6 p-4 rounded-2xl bg-[#141c15] border border-white/10">
            <p class="text-xs uppercase text-gray-500 mb-3">Immagine Profilo</p>
            <div class="flex items-center gap-4">
                <img :src="form.avatar_url || fallbackAvatar" alt="Avatar" class="w-16 h-16 rounded-full object-cover border border-white/20" />
                <div class="flex-1 space-y-2">
                    <input type="file" accept="image/*" @change="onAvatarSelected" class="w-full text-xs text-gray-300 file:mr-2 file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:bg-lime-300 file:text-black file:font-bold" />
                    <button :disabled="uploading || !avatarFile" class="bg-lime-300 text-black rounded-xl px-4 py-2 text-xs font-bold disabled:opacity-50" @click="uploadAvatar">
                        {{ uploading ? 'Upload...' : 'Carica immagine' }}
                    </button>
                </div>
            </div>
        </div>

        <form class="grid md:grid-cols-2 gap-3" @submit.prevent="save">
            <input v-model="form.name" type="text" required placeholder="Nome" class="w-full bg-[#141c15] border border-white/10 rounded-xl px-4 py-2 text-sm" />
            <input v-model="form.email" type="email" required placeholder="Email" class="w-full bg-[#141c15] border border-white/10 rounded-xl px-4 py-2 text-sm" />
            <input v-model="form.plan" type="text" required placeholder="Piano" class="w-full bg-[#141c15] border border-white/10 rounded-xl px-4 py-2 text-sm" />
            <input v-model="form.timezone" type="text" required placeholder="Timezone" class="w-full bg-[#141c15] border border-white/10 rounded-xl px-4 py-2 text-sm" />
            <select v-model="form.preferred_view" class="w-full bg-[#141c15] border border-white/10 rounded-xl px-4 py-2 text-sm">
                <option value="day">Giorno</option>
                <option value="month">Mese</option>
                <option value="year">Anno</option>
            </select>
            <textarea v-model="form.bio" rows="5" placeholder="Bio" class="md:col-span-2 w-full bg-[#141c15] border border-white/10 rounded-xl px-4 py-2 text-sm" />
            <button :disabled="saving" class="md:col-span-2 bg-lime-300 text-black rounded-xl py-3 text-sm font-bold disabled:opacity-50">
                {{ saving ? 'Salvataggio...' : 'Salva Profilo' }}
            </button>
        </form>
    </div>
</template>

<script setup>
import axios from 'axios'
import { reactive, ref, watch } from 'vue'

const props = defineProps({
    profile: { type: Object, required: true },
})

const emit = defineEmits(['updated'])

const form = reactive({
    name: '',
    email: '',
    plan: 'Free Plan',
    avatar_url: '',
    timezone: 'Europe/Rome',
    preferred_view: 'month',
    bio: '',
})

const saving = ref(false)
const uploading = ref(false)
const avatarFile = ref(null)
const fallbackAvatar = 'https://i.pravatar.cc/160?img=59'

watch(
    () => props.profile,
    (profile) => {
        Object.assign(form, profile)
    },
    { immediate: true, deep: true },
)

async function save() {
    saving.value = true
    try {
        const { data } = await axios.put('/api/profile', form)
        emit('updated', data.profile)
    } finally {
        saving.value = false
    }
}

function onAvatarSelected(event) {
    avatarFile.value = event.target.files?.[0] ?? null
}

async function uploadAvatar() {
    if (!avatarFile.value) {
        return
    }

    uploading.value = true
    try {
        const formData = new FormData()
        formData.append('avatar', avatarFile.value)

        const { data } = await axios.post('/api/profile/avatar', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })

        emit('updated', data.profile)
        Object.assign(form, data.profile)
        avatarFile.value = null
    } finally {
        uploading.value = false
    }
}
</script>
