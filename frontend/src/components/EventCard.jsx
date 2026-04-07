import React from 'react';
import { Link } from 'react-router-dom';

function EventCard({ event }) {
  return (
    <div style={{
      border: '1px solid #e0e0e0',
      padding: '20px',
      margin: '10px 0',
      borderRadius: '8px',
      boxShadow: '0 2px 5px rgba(0,0,0,0.05)',
      backgroundColor: '#fff'
    }}>
      <h2 style={{ margin: '0 0 10px', color: '#333' }}>{event.title}</h2>
      <p style={{ margin: '0 0 5px', color: '#555' }}>{event.description}</p>
      <p style={{ margin: '0 0 5px', fontSize: '0.9em', color: '#777' }}>
        {new Date(event.date).toLocaleString()} - {event.location}
      </p>
      <Link 
        to={`/events/${event.id}`}
        style={{
          display: 'inline-block',
          marginTop: '10px',
          padding: '8px 12px',
          backgroundColor: '#1976d2',
          color: '#fff',
          borderRadius: '4px',
          textDecoration: 'none'
        }}
      >
        Voir le détail
      </Link>
    </div>
  );
}

export default EventCard;