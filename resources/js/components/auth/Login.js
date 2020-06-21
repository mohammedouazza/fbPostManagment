import React from "react";

function Login() {
    const handleSubmit = e => {
        e.preventDefault();
        console.log(e);
        /*axios
            .post("/logout")
            .then(() => {
                console.log("logged out");
            })
            .catch(err => {
                console.log(err);
            });*/
    };
    return (
        <div className="container">
            <div className="row justify-content-center">
                <div className="col-md-8">
                    <div className="card">
                        <div className="card-header">Login</div>

                        <div className="card-body">
                            <form onSubmit={handleSubmit}>
                                <div className="form-group row">
                                    <label
                                        htmlFor="email"
                                        className="col-md-4 col-form-label text-md-right"
                                    >
                                        Email
                                    </label>

                                    <div className="col-md-6">
                                        <input
                                            id="email"
                                            type="email"
                                            name="email"
                                            required
                                            autoComplete="email"
                                            autoFocus
                                        />
                                    </div>
                                </div>

                                <div className="form-group row">
                                    <label
                                        htmlFor="password"
                                        className="col-md-4 col-form-label text-md-right"
                                    >
                                        Password
                                    </label>

                                    <div className="col-md-6">
                                        <input
                                            id="password"
                                            type="password"
                                            name="password"
                                            required
                                            autoComplete="current-password"
                                        />
                                    </div>
                                </div>

                                <div className="form-group row">
                                    <div className="col-md-6 offset-md-4">
                                        <div className="form-check">
                                            <input
                                                className="form-check-input"
                                                type="checkbox"
                                                name="remember"
                                                id="remember"
                                            />

                                            <label
                                                className="form-check-label"
                                                htmlFor="remember"
                                            >
                                                Remember me
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div className="form-group row mb-0">
                                    <div className="col-md-8 offset-md-4">
                                        <button
                                            type="submit"
                                            className="btn btn-primary"
                                        >
                                            Login
                                        </button>

                                        <a
                                            className="btn btn-link"
                                            href="/reset/password"
                                        >
                                            Forgot Your Password?
                                        </a>

                                        <a
                                            className="btn btn-link"
                                            href="/login/facebook"
                                        >
                                            Facebook Login
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Login;
