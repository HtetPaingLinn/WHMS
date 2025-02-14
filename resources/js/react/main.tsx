import React from 'react';
import ReactDOM from 'react-dom/client';
import MapComponent from './MapComponent';

ReactDOM.createRoot(document.getElementById('react-map')!).render(
    <React.StrictMode>
        <MapComponent />
    </React.StrictMode>
);
