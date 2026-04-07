// src/api.js
const API_BASE = "http://127.0.0.1:8000/api"; // l'URL de ton API Symfony

export async function fetchEvents() {
  const res = await fetch(`${API_BASE}/events`);
  if (!res.ok) throw new Error("Erreur lors du chargement des événements");
  return res.json();
}

export async function fetchEventById(id) {
  const res = await fetch(`${API_BASE}/events/${id}`);
  if (!res.ok) throw new Error("Erreur lors du chargement de l'événement");
  return res.json();
}
export const deleteEvent = async (id) => {
  const res = await fetch(`${API_BASE}/events/${id}`, {
    method: 'DELETE',
  });
  return res.json();
};