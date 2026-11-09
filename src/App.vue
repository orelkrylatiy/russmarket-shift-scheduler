<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'

type Shift = { id: number; employee: string; date: string; kind: string; start: string; end: string; color: string }
const employees = ['Анна Смирнова', 'Иван Петров', 'Мария Кузнецова', 'Алексей Орлов', 'Ольга Волкова']
const days = [
  { key: '2024-06-03', label: 'Пн', date: '3 июн' }, { key: '2024-06-04', label: 'Вт', date: '4 июн' },
  { key: '2024-06-05', label: 'Ср', date: '5 июн' }, { key: '2024-06-06', label: 'Чт', date: '6 июн' },
  { key: '2024-06-07', label: 'Пт', date: '7 июн' }, { key: '2024-06-08', label: 'Сб', date: '8 июн' },
  { key: '2024-06-09', label: 'Вс', date: '9 июн' },
]
const seed: Shift[] = [
  { id: 1, employee: employees[0], date: days[0].key, kind: 'Утренняя', start: '09:00', end: '18:00', color: 'blue' },
  { id: 2, employee: employees[0], date: days[2].key, kind: 'Утренняя', start: '09:00', end: '18:00', color: 'blue' },
  { id: 3, employee: employees[1], date: days[1].key, kind: 'Вечерняя', start: '14:00', end: '23:00', color: 'purple' },
  { id: 4, employee: employees[1], date: days[3].key, kind: 'Вечерняя', start: '14:00', end: '23:00', color: 'purple' },
  { id: 5, employee: employees[2], date: days[0].key, kind: 'Дневная', start: '10:00', end: '19:00', color: 'green' },
  { id: 6, employee: employees[3], date: days[4].key, kind: 'Утренняя', start: '09:00', end: '18:00', color: 'blue' },
]
const shifts = ref<Shift[]>(JSON.parse(localStorage.getItem('russmarket-shifts') || 'null') || seed)
const selectedEmployee = ref(employees[0]); const selectedDate = ref(days[0].key); const showModal = ref(false); const editingId = ref<number | null>(null); const saved = ref(false)
const form = ref({ kind: 'Утренняя', start: '09:00', end: '18:00', color: 'blue' })
const apiBase = '/russmarket-api/api'
const selected = computed(() => shifts.value.find(s => s.id === editingId.value))
function openCreate(employee: string, date: string) { selectedEmployee.value = employee; selectedDate.value = date; editingId.value = null; form.value = { kind: 'Утренняя', start: '09:00', end: '18:00', color: 'blue' }; showModal.value = true }
function openEdit(s: Shift) { selectedEmployee.value = s.employee; selectedDate.value = s.date; editingId.value = s.id; form.value = { kind: s.kind, start: s.start, end: s.end, color: s.color }; showModal.value = true }
async function saveShift() {
  const payload = { employee: selectedEmployee.value, date: selectedDate.value, ...form.value }
  if (editingId.value) { const item = shifts.value.find(s => s.id === editingId.value); if (item) Object.assign(item, payload); await fetch(`${apiBase}/shifts/${editingId.value}`, { method: 'PUT', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) }) }
  else { const response = await fetch(`${apiBase}/shifts`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) }); const created = await response.json(); shifts.value.push(created) }
  showModal.value = false; persist()
}
async function removeShift() { shifts.value = shifts.value.filter(s => s.id !== editingId.value); if (editingId.value) await fetch(`${apiBase}/shifts/${editingId.value}`, { method: 'DELETE' }); showModal.value = false; persist() }
function persist() { localStorage.setItem('russmarket-shifts', JSON.stringify(shifts.value)); saved.value = true; setTimeout(() => saved.value = false, 1800) }
function applyTemplate() { employees.forEach((employee, i) => { const date = days[(i + 1) % 5].key; shifts.value.push({ id: Date.now() + i, employee, date, kind: 'Дневная', start: '10:00', end: '19:00', color: 'green' }) }); persist() }
function cell(employee: string, date: string) { return shifts.value.filter(s => s.employee === employee && s.date === date) }
watch([selectedEmployee, selectedDate], () => { const s = selected.value; if (s && editingId.value) form.value = { kind: s.kind, start: s.start, end: s.end, color: s.color } })
onMounted(async () => { try { const response = await fetch(`${apiBase}/schedule?week=${days[0].key}`); if (!response.ok) return; const data = await response.json(); if (Array.isArray(data.shifts) && data.shifts.length) { shifts.value = data.shifts.map((s: Shift) => ({ ...s, id: Number(s.id) })); persist() } } catch { /* local seed remains available offline */ } })
</script>

<template>
  <div class="app-shell">
    <header class="topbar"><div class="brand"><span class="brand-mark">R</span><span>Рабочие графики</span></div><div class="top-actions"><button class="icon-btn">⌕</button><button class="avatar">МА</button><span class="chevron">⌄</span></div></header>
    <main class="content">
      <div class="breadcrumbs">Графики <span>/</span> Июнь 2024</div>
      <section class="hero"><div><h1>График смен сотрудников</h1><p>Создайте и настройте расписание работы команды</p></div><div class="hero-actions"><button class="outline" @click="applyTemplate">▦ <span>Применить шаблон</span></button><button class="primary" @click="openCreate(employees[0], days[0].key)">＋ Добавить смену</button></div></section>
      <section class="toolbar"><button class="period">‹</button><strong>3 — 9 июня 2024</strong><button class="period">›</button><span class="divider"></span><button class="today">Сегодня</button><label class="select-label">Вид <select><option>Неделя</option><option>Месяц</option></select></label><span class="saved" v-if="saved">Сохранено</span></section>
      <section class="schedule-card"><div class="grid schedule-head"><div class="employee-head">Сотрудник</div><div v-for="day in days" :key="day.key" class="day-head"><b>{{ day.label }}</b><span>{{ day.date }}</span></div></div><div v-for="employee in employees" :key="employee" class="grid schedule-row"><div class="employee-cell"><span class="person">{{ employee.split(' ').map(x => x[0]).join('') }}</span><span>{{ employee }}</span></div><div v-for="day in days" :key="day.key" class="day-cell" @dblclick="openCreate(employee, day.key)"><button v-for="shift in cell(employee, day.key)" :key="shift.id" class="shift" :class="shift.color" @click.stop="openEdit(shift)"><b>{{ shift.kind }}</b><small>{{ shift.start }} — {{ shift.end }}</small></button><button v-if="!cell(employee, day.key).length" class="add-cell" @click="openCreate(employee, day.key)">＋</button></div></div><div class="legend"><span><i class="dot blue"></i> Утренняя смена</span><span><i class="dot purple"></i> Вечерняя смена</span><span><i class="dot green"></i> Дневная смена</span><span class="hint">Дважды кликните по ячейке, чтобы добавить смену</span></div></section>
      <section class="info"><div class="info-icon">i</div><div><b>Совет</b><p>Вы можете назначать несколько смен одному сотруднику в течение недели. Нажмите на смену, чтобы изменить её.</p></div></section>
    </main>
    <div v-if="showModal" class="modal-backdrop" @click.self="showModal = false"><div class="modal"><div class="modal-head"><div><h2>{{ editingId ? 'Изменить смену' : 'Новая смена' }}</h2><p>{{ selectedEmployee }} · {{ days.find(d => d.key === selectedDate)?.label }} {{ days.find(d => d.key === selectedDate)?.date }}</p></div><button class="close" @click="showModal = false">×</button></div><label>Тип смены<select v-model="form.kind"><option>Утренняя</option><option>Дневная</option><option>Вечерняя</option></select></label><div class="form-row"><label>Начало<input v-model="form.start" type="time"></label><label>Окончание<input v-model="form.end" type="time"></label></div><label>Цвет смены<select v-model="form.color"><option value="blue">Синий</option><option value="green">Зелёный</option><option value="purple">Фиолетовый</option></select></label><div class="modal-actions"><button v-if="editingId" class="danger" @click="removeShift">Удалить</button><span></span><button class="outline" @click="showModal = false">Отмена</button><button class="primary" @click="saveShift">Сохранить</button></div></div></div>
  </div>
</template>
