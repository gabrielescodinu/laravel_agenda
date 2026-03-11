<template>
    <div class="flex-1 bg-[#0d120d] rounded-[3rem] border border-white/5 p-6 md:p-8 overflow-y-auto custom-scrollbar">
        <h1 class="text-2xl font-black mb-6">Configurazione Profilo</h1>

        <form class="grid md:grid-cols-2 gap-3" @submit.prevent="save">
            <input v-model="form.name" type="text" required placeholder="Nome" class="w-full bg-[#141c15] border border-white/10 rounded-xl px-4 py-2 text-sm" />
            <input v-model="form.email" type="email" required placeholder="Email" class="w-full bg-[#141c15] border border-white/10 rounded-xl px-4 py-2 text-sm" />
            <input v-model="form.plan" type="text" required placeholder="Piano" class="w-full bg-[#141c15] border border-white/10 rounded-xl px-4 py-2 text-sm" />
            <input v-model="form.avatar_url" type="url" placeholder="Avatar URL" class="w-full bg-[#141c15] border border-white/10 rounded-xl px-4 py-2 text-sm" />
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
</script>
