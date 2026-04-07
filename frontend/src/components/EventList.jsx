import React, { useEffect, useState } from "react";
import { fetchEvents } from "../api";
import EventCard from "../components/EventCard";

const EventList = () => {
  const [events, setEvents] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchEvents()
      .then(data => setEvents(data))
      .catch(err => console.error(err))
      .finally(() => setLoading(false));
  }, []);

  if (loading) return <p>Chargement des événements...</p>;

  return (
    <div style={{ maxWidth: '800px', margin: '0 auto', padding: '20px' }}>
      <h1 style={{ textAlign: 'center', color: '#1976d2' }}>Liste des événements</h1>
      {events.length === 0 ? (
        <p style={{ textAlign: 'center', color: '#777' }}>Aucun événement disponible.</p>
      ) : (
        events.map(event => <EventCard key={event.id} event={event} />)
      )}
    </div>
  );
};

export default EventList;