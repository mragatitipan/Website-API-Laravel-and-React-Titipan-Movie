import React from 'react';
import { Link } from 'react-router-dom';

function NotFound() {
    return (
        <div className="container text-center py-5">
            <h1 className="display-1">404</h1>
            <p className="lead">Page not found</p>
            <Link to="/dashboard" className="btn btn-primary">
                Go to Dashboard
            </Link>
        </div>
    );
}

export default NotFound;
