import { useEffect, useState } from "react";
import { Button, Col, Container, Form } from "react-bootstrap";
import { Link, useNavigate } from "react-router";
import type { UserLoginData } from "../../types/auth";

export default function SignIn() {
	const [userData, setUserData] = useState<UserLoginData>({ email: "", password: "" });
	const [errorsArray, setErrorsArray] = useState<string[]>([]);
	const navigate = useNavigate();

	async function handleUserLogin(e: any) {
		e.preventDefault();
		try {
			const response = await fetch("http://productivityapp.local/sign-in", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
				},
				body: JSON.stringify(userData),
			});
			const data = await response.json();
			if (data.errors) {
				setErrorsArray(data.errors);
			} else {
				localStorage.setItem("jwt", data.token);
				console.log(data.token);
				navigate("/dashboard");
			}
		} catch (error) {
			setErrorsArray(["Server error. Try again."]);
		}
	}

	useEffect(() => {
		document.title = "ProductivityApp - Sign In";
	});

	return (
		<div className='d-flex justify-content-center align-items-center min-vh-100'>
			<Container>
				<Col sm='10' md='8' lg='6' xl='5' xxl='4'>
					<div className='mb-4 text-center'>
						<h2>Sign In</h2>
						<p>To visit on dashboard please sign in.</p>
					</div>
					<Form onSubmit={handleUserLogin}>
						<Form.Group className='mb-3'>
							<Form.Floating>
								<Form.Control value={userData.email} onChange={e => setUserData(prevState => ({ ...prevState, email: e.target.value }))} type='email' placeholder='Address email' />
								<Form.Label>Address email</Form.Label>
							</Form.Floating>
						</Form.Group>

						<Form.Group className='mb-3'>
							<Form.Floating>
								<Form.Control value={userData.password} onChange={e => setUserData(prevState => ({ ...prevState, password: e.target.value }))} type='password' placeholder='Password' />
								<Form.Label>Password</Form.Label>
							</Form.Floating>
						</Form.Group>
						{errorsArray.length > 0 ? (
							<div className='mb-3'>
								<div className='alert alert-danger'>
									{errorsArray.map((error, index) => (
										<div key={index}>{error}</div>
									))}
								</div>
							</div>
						) : (
							""
						)}
						<Button className='w-100' type='submit'>
							Login
						</Button>
					</Form>

					<div className='mt-4 d-flex justify-content-center'>
						<p className='me-1'>If you have not an account, please</p>
						<Link to='/sign-up'>sign up</Link>.
					</div>
				</Col>
			</Container>
		</div>
	);
}
