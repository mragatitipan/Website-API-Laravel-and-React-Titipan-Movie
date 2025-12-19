/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page.
 */

import React from 'react';
import { createRoot } from 'react-dom/client';
import App from './App.jsx';

// Mount React App
const container = document.getElementById('app');
if (container) {
    const root = createRoot(container);
    root.render(<App />);
}
