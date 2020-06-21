import React from "react";
import axios from "axios";

function Home() {
    const handleSubmit = e => {
        e.preventDefault();

        axios
            .post("/logout")
            .then(() => {
                console.log("logged out");
            })
            .catch(err => {
                console.log(err);
            });
    };
    return (
        <form onSubmit={handleSubmit}>
            <button type="submit" className="dropdown-item">
                Logout
            </button>
        </form>
    );
}

export default Home;
