import React, { useEffect, useState } from "react";
import { fetchEvents, deleteEvent } from "../api";
import { Link, useNavigate } from "react-router-dom";

const Dashboard = () => {
  const [events, setEvents] = useState([]);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  const loadEvents = () => {
    setLoading(true);
    fetchEvents()
      .then(data => setEvents(data))
      .catch(err => console.error(err))
      .finally(() => setLoading(false));
  };

  useEffect(() => {
    loadEvents();
  }, []);

  const handleDelete = async (id) => {
    if (!window.confirm("Voulez-vous vraiment supprimer cet événement ?")) return;
    try {
      await deleteEvent(id);
      loadEvents(); // recharge la liste
    } catch (err) {
      console.error(err);
      alert("Erreur lors de la suppression !");
    }
  };

  if (loading) return <p>Chargement du dashboard...</p>;

  return (
    <div style={{ maxWidth: "900px", margin: "20px auto", padding: "10px" }}>
      <h1>Admin Dashboard</h1>
      <button
        style={{ marginBottom: "20px", padding: "10px 15px", background: "#28a745", color: "#fff", border: "none", borderRadius: "5px" }}
        onClick={() => navigate("/events/new")}
      >
        Créer un nouvel événement
      </button>

      <table style={{ width: "100%", borderCollapse: "collapse" }}>
        <thead>
          <tr style={{ borderBottom: "1px solid #ddd" }}>
            <th>Titre</th>
            <th>Date</th>
            <th>Lieu</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          {events.map(event => (
            <tr key={event.id} style={{ borderBottom: "1px solid #eee" }}>
              <td>{event.title}</td>
              <td>{new Date(event.date).toLocaleString()}</td>
              <td>{event.location}</td>
              <td>
                <button 
                  style={{ marginRight: "10px" }}
                  onClick={() => navigate(`/events/${event.id}/edit`)}
                >
                  Éditer
                </button>
                <button 
                  style={{ color: "#fff", background: "#dc3545", border: "none", padding: "5px 10px", borderRadius: "3px" }}
                  onClick={() => handleDelete(event.id)}
                >
                  Supprimer
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default Dashboard;