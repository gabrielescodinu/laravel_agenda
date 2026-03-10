<template>
    <div class="h-screen flex p-4 md:p-6 gap-6 overflow-hidden">
        <aside class="hidden lg:flex flex-col w-20 bg-[#0d120d] rounded-[2.5rem] border border-white/5 items-center py-8 gap-10">
            <div class="w-10 h-10 bg-lime-300 rounded-xl flex items-center justify-center text-black">
                <LayoutGrid class="w-6 h-6" />
            </div>
            <nav class="flex flex-col gap-8 flex-1 text-gray-500">
                <Home class="w-5 h-5" />
                <BarChart3 class="w-5 h-5" />
                <CalendarDays class="w-5 h-5 text-lime-300" />
                <Mail class="w-5 h-5" />
                <Users class="w-5 h-5" />
            </nav>
            <div class="flex flex-col gap-8 text-gray-500">
                <Settings class="w-5 h-5" />
                <button class="hover:text-white transition" @click="logout">
                    <LogOut class="w-5 h-5" />
                </button>
            </div>
        </aside>

        <main class="flex-1 flex flex-col gap-6 overflow-hidden">
            <header class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <img :src="user.avatar" alt="Profilo" class="w-10 h-10 rounded-full object-cover grayscale" />
                    <div>
                        <h2 class="text-sm font-bold">Bentornato, {{ firstName }}</h2>
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest">{{ user.plan }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative hidden sm:block">
                        <Search class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-500" />
                        <input v-model="search" type="text" placeholder="Cerca eventi/task..." class="bg-[#0d120d] border border-white/5 rounded-full py-2 pl-12 pr-6 text-sm focus:outline-none focus:border-lime-300/30 w-64" />
                    </div>
                    <button class="w-10 h-10 bg-[#0d120d] rounded-full flex items-center justify-center border border-white/5">
                        <Bell class="w-5 h-5" />
                    </button>
                </div>
            </header>

            <div class="flex-1 bg-[#0d120d] rounded-[3rem] border border-white/5 p-6 md:p-8 flex flex-col relative overflow-hidden">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                    <div class="flex items-center gap-4">
                        <h1 class="text-2xl font-black capitalize">{{ currentMonthLabel }}</h1>
                        <button class="bg-lime-300 text-black px-4 py-2 rounded-xl text-xs font-bold flex items-center gap-2 hover:bg-white transition" @click="openTaskComposer = !openTaskComposer">
                            <Plus class="w-4 h-4" /> Nuovo Task
                        </button>
                    </div>
                    <div class="flex bg-black/40 p-1 rounded-xl border border-white/5">
                        <button class="px-4 py-1.5 text-xs font-bold rounded-lg text-gray-400">Giorno</button>
                        <button class="px-4 py-1.5 text-xs font-bold rounded-lg bg-[#141c15] text-white">Mese</button>
                        <button class="px-4 py-1.5 text-xs font-bold rounded-lg text-gray-400">Anno</button>
                    </div>
                </div>

                <form v-if="openTaskComposer" class="mb-5 grid md:grid-cols-3 gap-2" @submit.prevent="createTask">
                    <input v-model="newTask.title" type="text" required placeholder="Titolo task" class="bg-[#141c15] border border-white/10 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-lime-300/40" />
                    <input v-model="newTask.type" type="text" required placeholder="Tipo (es. Creativita)" class="bg-[#141c15] border border-white/10 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-lime-300/40" />
                    <input v-model="newTask.dueDate" type="date" required class="bg-[#141c15] border border-white/10 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-lime-300/40" />
                    <input v-model="newTask.startTime" type="time" required class="bg-[#141c15] border border-white/10 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-lime-300/40" />
                    <input v-model="newTask.endTime" type="time" required class="bg-[#141c15] border border-white/10 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-lime-300/40" />
                    <select v-model="newTask.color" class="bg-[#141c15] border border-white/10 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-lime-300/40">
                        <option value="lime">Lime</option>
                        <option value="indigo">Indigo</option>
                        <option value="blue">Blue</option>
                    </select>
                    <input v-model="newTask.participantNames" type="text" placeholder="Partecipanti: Alex, Marta" class="md:col-span-2 bg-[#141c15] border border-white/10 rounded-xl px-4 py-2 text-sm focus:outline-none focus:border-lime-300/40" />
                    <button :disabled="savingTask" class="bg-lime-300 text-black rounded-xl px-4 py-2 text-xs font-bold disabled:opacity-50">
                        {{ savingTask ? 'Salvataggio...' : 'Aggiungi Task' }}
                    </button>
                </form>

                <div class="flex-1 flex flex-col min-h-0">
                    <div class="calendar-grid border-b border-white/5 mb-2">
                        <div v-for="(day, index) in weekDays" :key="day" class="py-2 text-center text-[10px] font-bold uppercase" :class="index === 4 ? 'text-lime-300' : 'text-gray-500'">
                            {{ day }}
                        </div>
                    </div>

                    <div class="calendar-grid flex-1">
                        <div v-for="(cell, index) in calendarCells" :key="`${cell.day || 'empty'}-${index}`" class="border-r border-b border-white/5 p-2 min-h-[100px] transition" :class="cell.day ? 'hover:bg-white/5' : 'opacity-20'">
                            <template v-if="cell.day">
                                <span :class="isToday(cell.day) ? 'text-lime-300 font-bold' : 'text-gray-400'">{{ cell.day }}</span>
                                <div class="mt-2 space-y-2">
                                    <button v-for="event in eventsByDay[cell.day] || []" :key="event.id" class="w-full p-2 rounded-lg text-[10px] font-bold leading-tight border border-white/5 text-left" :style="eventCardStyle(event.color)" @click="openEvent(event)">
                                        {{ event.title }}
                                    </button>
                                    <button v-for="task in tasksByDay[cell.day] || []" :key="`task-${task.id}`" class="w-full p-2 rounded-lg text-[10px] font-bold leading-tight border text-left" :class="task.completed ? 'border-emerald-300/20 bg-emerald-300/10 text-emerald-200' : 'border-amber-300/20 bg-amber-300/15 text-amber-100'" @click="openTask(task)">
                                        {{ task.title }}
                                        <span class="ml-1 opacity-80">{{ taskTimeRange(task) }}</span>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div v-if="selectedEvent || selectedTask" class="absolute inset-0 bg-black/40 backdrop-blur-[1px] z-40" @click="closeModals" />

                <div v-if="selectedEvent" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[350px] glass-modal rounded-[2.5rem] p-8 shadow-2xl z-50">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-xl font-black">{{ selectedEvent.title }}</h3>
                        <button class="text-gray-500 hover:text-white transition" @click="selectedEvent = null">
                            <X class="w-5 h-5" />
                        </button>
                    </div>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center" :style="modalIconStyle(selectedEvent.color)">
                                <component :is="iconFor(selectedEvent.icon)" class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-bold">Tipo</p>
                                <p class="text-sm font-semibold">{{ selectedEvent.type }}</p>
                            </div>
                        </div>
                        <div class="flex gap-10">
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-bold">Orario</p>
                                <p class="text-sm font-semibold">{{ formatEventTime(selectedEvent) }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-bold">Data</p>
                                <p class="text-sm font-semibold">{{ formatEventDate(selectedEvent) }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase font-bold mb-3">Partecipanti</p>
                            <div class="flex -space-x-3">
                                <img v-for="(participant, index) in selectedEvent.participants" :key="`${participant.name}-${index}`" :src="participant.avatar || avatarForName(participant.name, index)" :alt="participant.name" class="w-8 h-8 rounded-full border-2 border-[#0d120d]" />
                            </div>
                        </div>
                        <button class="w-full bg-lime-300 text-black py-4 rounded-2xl font-black uppercase text-xs tracking-widest">Inizia Sessione</button>
                    </div>
                </div>

                <div v-if="selectedTask" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[350px] glass-modal rounded-[2.5rem] p-8 shadow-2xl z-50">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-4xl font-black leading-none">{{ selectedTask.title }}</h3>
                        <button class="text-gray-500 hover:text-white transition" @click="selectedTask = null">
                            <X class="w-5 h-5" />
                        </button>
                    </div>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center" :style="modalIconStyle(selectedTask.color)">
                                <component :is="iconFor(selectedTask.icon)" class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-bold">TIPO</p>
                                <p class="text-sm font-semibold">{{ selectedTask.type }}</p>
                            </div>
                        </div>

                        <div class="flex gap-10">
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-bold">ORARIO</p>
                                <p class="text-sm font-semibold">{{ taskTimeRange(selectedTask) }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-bold">DATA</p>
                                <p class="text-sm font-semibold">{{ formatTaskDate(selectedTask) }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] text-gray-500 uppercase font-bold mb-3">PARTECIPANTI</p>
                            <div class="flex -space-x-3">
                                <img v-for="(participant, index) in selectedTask.participants" :key="`${participant.name}-${index}`" :src="participant.avatar || avatarForName(participant.name, index)" :alt="participant.name" class="w-8 h-8 rounded-full border-2 border-[#0d120d]" />
                            </div>
                        </div>

                        <button class="w-full bg-lime-300 text-black py-4 rounded-2xl font-black uppercase text-xs tracking-widest">INIZIA SESSIONE</button>
                    </div>
                </div>
            </div>
        </main>

        <aside class="hidden xl:flex flex-col w-[350px] gap-6">
            <div class="bg-[#0d120d] rounded-[2.5rem] border border-white/5 p-8">
                <div class="flex justify-between items-center mb-6">
                    <h4 class="font-bold capitalize">{{ selectedDateLabel }}</h4>
                    <div class="flex gap-2">
                        <button class="text-gray-500 hover:text-white" @click="prevMonth"><ChevronLeft class="w-4 h-4" /></button>
                        <button class="text-gray-500 hover:text-white" @click="nextMonth"><ChevronRight class="w-4 h-4" /></button>
                    </div>
                </div>
                <div class="grid grid-cols-7 gap-y-4 text-center text-xs">
                    <div v-for="d in miniWeek" :key="d" class="text-gray-600 font-bold">{{ d }}</div>
                    <div v-for="day in daysInMonth" :key="`mini-${day}`" class="mx-auto flex items-center justify-center w-6 h-6 rounded-lg" :class="day === selectedDay ? 'bg-lime-300 text-black font-bold' : 'text-gray-500'">
                        {{ day }}
                    </div>
                </div>
            </div>

            <div class="bg-[#0d120d] rounded-[2.5rem] border border-white/5 p-8 flex-1 flex flex-col min-h-0">
                <h4 class="font-bold mb-6">Focus sui tuoi obiettivi</h4>
                <div class="relative w-40 h-40 mx-auto mb-8">
                    <svg class="w-full h-full -rotate-90" viewBox="0 0 160 160">
                        <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="12" fill="transparent" class="text-white/5" />
                        <circle cx="80" cy="80" r="70" stroke="currentColor" stroke-width="12" fill="transparent" class="text-lime-300 transition-all duration-700" stroke-linecap="round" stroke-dasharray="440" :stroke-dashoffset="progressOffset" />
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center flex-col">
                        <span class="text-4xl font-black">{{ progressPercent }}%</span>
                        <span class="text-[10px] text-gray-500 uppercase font-bold tracking-tighter">Completato</span>
                    </div>
                </div>

                <div class="space-y-4 overflow-y-auto custom-scrollbar flex-1 pr-2">
                    <div v-for="task in tasks" :key="task.id" class="w-full p-4 bg-[#141c15] rounded-2xl border border-white/5 hover:border-lime-300/30 transition">
                        <div class="flex items-center justify-between gap-2">
                            <button class="flex-1 text-left" @click="openTask(task)">
                                <p class="text-sm font-medium" :class="task.completed ? 'text-gray-500 line-through' : ''">{{ task.title }}</p>
                                <p class="text-[10px] text-gray-500">{{ taskTimeRange(task) }} · {{ formatTaskDate(task) }}</p>
                            </button>
                            <button class="w-5 h-5 rounded border flex items-center justify-center" :class="task.completed ? 'border-lime-300 bg-lime-300/10' : 'border-white/20'" @click.stop="toggleTask(task)">
                                <Check v-if="task.completed" class="w-3 h-3 text-lime-300" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</template>

<script setup>
import axios from 'axios'
import { computed, onMounted, reactive, ref } from 'vue'
import {
    BarChart3,
    Bell,
    BookOpen,
    CalendarDays,
    Check,
    ChevronLeft,
    ChevronRight,
    Clock3,
    Home,
    LayoutGrid,
    LogOut,
    Mail,
    Palette,
    Plus,
    Search,
    Settings,
    Users,
    X,
} from 'lucide-vue-next'

const user = reactive({
    name: 'Alex Smith',
    plan: 'Premium Plan',
    avatar: 'https://i.pravatar.cc/160?img=59',
})

const search = ref('')
const tasks = ref([])
const events = ref([])
const selectedEvent = ref(null)
const selectedTask = ref(null)
const openTaskComposer = ref(false)
const savingTask = ref(false)

const today = new Date()
const currentMonth = ref(new Date(today.getFullYear(), today.getMonth(), 1))
const selectedDay = ref(today.getDate())
const newTask = reactive(defaultTaskForm(today))

const weekDays = ['Lun', 'Mar', 'Mer', 'Gio', 'Ven', 'Sab', 'Dom']
const miniWeek = ['L', 'M', 'M', 'G', 'V', 'S', 'D']

const iconByName = {
    palette: Palette,
    'book-open': BookOpen,
    'clock-3': Clock3,
}

const firstName = computed(() => user.name.split(' ')[0] || user.name)

const currentMonthLabel = computed(() => {
    return currentMonth.value.toLocaleDateString('it-IT', {
        month: 'long',
        year: 'numeric',
    })
})

const daysInMonth = computed(() => {
    return new Date(currentMonth.value.getFullYear(), currentMonth.value.getMonth() + 1, 0).getDate()
})

const calendarCells = computed(() => {
    const firstWeekday = (currentMonth.value.getDay() + 6) % 7
    const cells = []

    for (let i = 0; i < firstWeekday; i += 1) {
        cells.push({ day: null })
    }

    for (let day = 1; day <= daysInMonth.value; day += 1) {
        cells.push({ day })
    }

    return cells
})

const filteredEvents = computed(() => {
    const term = search.value.trim().toLowerCase()
    if (!term) {
        return events.value
    }

    return events.value.filter((event) => {
        return event.title.toLowerCase().includes(term) || event.type.toLowerCase().includes(term)
    })
})

const filteredTasks = computed(() => {
    const term = search.value.trim().toLowerCase()
    if (!term) {
        return tasks.value
    }

    return tasks.value.filter((task) => {
        return task.title.toLowerCase().includes(term) || task.type.toLowerCase().includes(term)
    })
})

const eventsByDay = computed(() => {
    const grouped = {}

    for (const event of filteredEvents.value) {
        const day = new Date(event.start_at).getDate()
        if (!grouped[day]) {
            grouped[day] = []
        }
        grouped[day].push(event)
    }

    return grouped
})

const tasksByDay = computed(() => {
    const grouped = {}

    for (const task of filteredTasks.value) {
        if (!task.due_date) {
            continue
        }

        const day = Number(task.due_date.split('-')[2])

        if (!grouped[day]) {
            grouped[day] = []
        }

        grouped[day].push(task)
    }

    return grouped
})

const progressPercent = computed(() => {
    if (!tasks.value.length) {
        return 0
    }

    const completed = tasks.value.filter((task) => task.completed).length
    return Math.round((completed / tasks.value.length) * 100)
})

const progressOffset = computed(() => 440 - (440 * progressPercent.value) / 100)

const selectedDateLabel = computed(() => {
    return new Date(currentMonth.value.getFullYear(), currentMonth.value.getMonth(), selectedDay.value).toLocaleDateString('it-IT', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    })
})

function defaultTaskForm(date) {
    return {
        title: '',
        type: 'Creativita',
        dueDate: toLocalDate(date),
        startTime: '11:00',
        endTime: '12:30',
        color: 'lime',
        participantNames: 'Alex, Marta',
    }
}

function monthQuery() {
    const year = currentMonth.value.getFullYear()
    const month = String(currentMonth.value.getMonth() + 1).padStart(2, '0')
    return `${year}-${month}`
}

function toLocalDate(date) {
    const year = date.getFullYear()
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const day = String(date.getDate()).padStart(2, '0')
    return `${year}-${month}-${day}`
}

function isToday(day) {
    return (
        day === today.getDate() &&
        currentMonth.value.getMonth() === today.getMonth() &&
        currentMonth.value.getFullYear() === today.getFullYear()
    )
}

function eventCardStyle(color) {
    if (color === 'indigo') {
        return { backgroundColor: 'rgba(99, 102, 241, 0.2)', color: '#a5b4fc' }
    }

    if (color === 'blue') {
        return { backgroundColor: 'rgba(59, 130, 246, 0.2)', color: '#93c5fd' }
    }

    return { backgroundColor: 'rgba(190, 242, 100, 0.2)', color: '#bef264' }
}

function modalIconStyle(color) {
    if (color === 'indigo') {
        return { backgroundColor: 'rgba(99, 102, 241, 0.25)', color: '#a5b4fc' }
    }

    if (color === 'blue') {
        return { backgroundColor: 'rgba(59, 130, 246, 0.25)', color: '#93c5fd' }
    }

    return { backgroundColor: 'rgba(190, 242, 100, 0.25)', color: '#bef264' }
}

function iconFor(icon) {
    return iconByName[icon] || BookOpen
}

function openEvent(event) {
    selectedTask.value = null
    selectedEvent.value = event
}

function openTask(task) {
    selectedEvent.value = null
    selectedTask.value = task
}

function closeModals() {
    selectedEvent.value = null
    selectedTask.value = null
}

function avatarForName(name, index) {
    return `https://i.pravatar.cc/80?u=${encodeURIComponent(name || `u-${index}`)}`
}

function participantsFromText(text) {
    return text
        .split(',')
        .map((name) => name.trim())
        .filter(Boolean)
        .map((name) => ({
            name,
            avatar: avatarForName(name, 0),
        }))
}

function formatEventTime(event) {
    const start = new Date(event.start_at).toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' })
    const end = new Date(event.end_at).toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' })
    return `${start} - ${end}`
}

function formatEventDate(event) {
    return new Date(event.start_at).toLocaleDateString('it-IT', {
        day: 'numeric',
        month: 'long',
    })
}

function taskTimeRange(task) {
    if (task.start_time && task.end_time) {
        return `${task.start_time.slice(0, 5)} - ${task.end_time.slice(0, 5)}`
    }

    return task.time || '--:--'
}

function formatTaskDate(task) {
    if (!task.due_date) {
        return '--'
    }

    const [year, month, day] = task.due_date.split('-')
    return new Date(Number(year), Number(month) - 1, Number(day)).toLocaleDateString('it-IT', {
        day: 'numeric',
        month: 'long',
    })
}

async function loadDashboard() {
    const { data } = await axios.get('/api/dashboard', {
        params: { month: monthQuery() },
    })

    user.name = data.user.name
    user.plan = data.user.plan
    user.avatar = data.user.avatar
    events.value = data.calendar.events
    tasks.value = data.tasks
}

async function toggleTask(task) {
    const { data } = await axios.patch(`/api/tasks/${task.id}/toggle`)
    tasks.value = tasks.value.map((item) => (item.id === task.id ? data.task : item))
    if (selectedTask.value?.id === task.id) {
        selectedTask.value = data.task
    }
}

async function createTask() {
    savingTask.value = true

    try {
        const { data } = await axios.post('/api/tasks', {
            title: newTask.title,
            type: newTask.type,
            time: newTask.startTime,
            due_date: newTask.dueDate,
            start_time: newTask.startTime,
            end_time: newTask.endTime,
            color: newTask.color,
            participants: participantsFromText(newTask.participantNames),
        })

        tasks.value.push(data.task)
        Object.assign(newTask, defaultTaskForm(today))
        openTaskComposer.value = false
    } finally {
        savingTask.value = false
    }
}

async function prevMonth() {
    currentMonth.value = new Date(currentMonth.value.getFullYear(), currentMonth.value.getMonth() - 1, 1)
    selectedDay.value = 1
    await loadDashboard()
}

async function nextMonth() {
    currentMonth.value = new Date(currentMonth.value.getFullYear(), currentMonth.value.getMonth() + 1, 1)
    selectedDay.value = 1
    await loadDashboard()
}

async function logout() {
    await axios.post('/logout')
    window.location.href = '/login'
}

onMounted(async () => {
    await loadDashboard()
})
</script>
