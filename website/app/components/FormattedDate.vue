<script setup lang="ts">
import { computed, toRef } from 'vue'

const props = defineProps<{ date?: string | null }>()
// expose a reactive ref for the template
const date = toRef(props, 'date')

function getDaySuffix(day: number): string {
  if (day > 3 && day < 21) return 'th'
  switch (day % 10) {
    case 1: return 'st'
    case 2: return 'nd'
    case 3: return 'rd'
    default: return 'th'
  }
}

const formattedDate = computed(() => {
  if (!date.value) return ''                  // empty if no date
  // if the string doesn't include a timezone, assume UTC by appending 'Z'
  const iso = (typeof date.value === 'string' && !/Z|[+\-]\d{2}:\d{2}$/.test(date.value))
      ? `${date.value}Z`
      : date.value

  const d = new Date(iso)
  if (isNaN(d.getTime())) return date.value  // fallback: show raw string

  const day = d.getUTCDate()
  const month = d.toLocaleString('en-GB', { month: 'long', timeZone: 'UTC' })
  const year = d.getUTCFullYear()

  return `${day}${getDaySuffix(day)} ${month} ${year}`
})
</script>

<template>
  <!-- use the prop name 'date' (not props.date) and the computed string -->
  <time :datetime="date">{{ formattedDate }}</time>
</template>
