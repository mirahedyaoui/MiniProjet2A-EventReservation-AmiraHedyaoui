// src/pages/EventDetail.jsx
import React, { useEffect, useState } from "react";
import { useParams, Link } from "react-router-dom";
import { fetchEventById } from "../api";

const EventDetail = () => {
  const { id } = useParams();
  const [event, setEvent] = useState(null);

  useEffect(() => {
    fetchEventById(id)
      .then(data => setEvent(data))
      .catch(err => console.error(err));
  }, [id]);

  if (!event) return <p>Chargement de l'événement...</p>;

  return (
    <div>
      <h1>{event.title}</h1>
      <p>{event.description}</p>
      <p>Date : {new Date(event.date).toLocaleString()}</p>
      <p>Lieu : {event.location}</p>
      <Link to="/">Retour à la liste</Link>
    </div>
  );
};

export default EventDetail;