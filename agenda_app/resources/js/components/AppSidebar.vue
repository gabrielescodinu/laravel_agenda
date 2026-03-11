<template>
    <aside class="hidden lg:flex flex-col w-20 bg-[#0d120d] rounded-[2.5rem] border border-white/5 items-center py-8 gap-10">
        <div class="w-10 h-10 bg-lime-300 rounded-xl flex items-center justify-center text-black">
            <LayoutGrid class="w-6 h-6" />
        </div>

        <nav class="flex flex-col gap-8 flex-1 text-gray-500">
            <button class="hover:text-white transition" :class="activeView === 'agenda' ? 'text-lime-300' : ''" @click="$emit('navigate', 'agenda')">
                <CalendarDays class="w-5 h-5" />
            </button>
            <button class="hover:text-white transition" :class="activeView === 'profile' ? 'text-lime-300' : ''" @click="$emit('navigate', 'profile')">
                <User class="w-5 h-5" />
            </button>
        </nav>

        <button class="text-gray-500 hover:text-white transition" @click="$emit('logout')">
            <LogOut class="w-5 h-5" />
        </button>
    </aside>
</template>

<script setup>
import { CalendarDays, LayoutGrid, LogOut, User } from 'lucide-vue-next'

defineProps({
    activeView: { type: String, required: true },
})

defineEmits(['navigate', 'logout'])
</script>
