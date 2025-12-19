import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';

// Import Pages
import Dashboard from './pages/Dashboard';
import Movies from './pages/Movies';
import Sync from './pages/Sync';
import NotFound from './pages/NotFound';

// Import Layout
import Layout from './components/Layout/Layout';

function App() {
    return (
        <Router>
            <Layout>
                <Routes>
                    {/* Dashboard */}
                    <Route path="/" element={<Navigate to="/dashboard" replace />} />
                    <Route path="/dashboard" element={<Dashboard />} />
                    
                    {/* Movies Management */}
                    <Route path="/movies" element={<Movies />} />
                    
                    {/* API Sync */}
                    <Route path="/sync" element={<Sync />} />
                    
                    {/* 404 */}
                    <Route path="*" element={<NotFound />} />
                </Routes>
            </Layout>
        </Router>
    );
}

export default App;
