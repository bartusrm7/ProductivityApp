import { useEffect, useState } from "react";
import { Button, Col, Container, Form } from "react-bootstrap";
import { Link } from "react-router";
import type { UserRegistrationData } from "../../types/auth";

export default function SignUp() {
	const [userData, setUserData] = useState<UserRegistrationData>({ name: "", email: "", password: "" });
	const [isRegisterSuccessful, setIsRegisterSuccessful] = useState<boolean>(false);
	const [errorsArray, setErrorsArray] = useState<[]>([]);

	async function handleUserRegistartion(e: any) {
		e.preventDefault();
		try {
			const response = await fetch("http://productivityapp.local/sign-up", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
				},
				body: JSON.stringify(userData),
			});
			if (!response.ok) {
				throw new Error("Error with response.");
			}
			const data = await response.json();
			console.log(data);
			if (data.errors) {
				setErrorsArray(data.errors.errors);
			} else {
				setIsRegisterSuccessful(true);
			}
		} catch (error) {
			return "Registration is not successful!";
		}
	}
	useEffect(() => {
		document.title = "ProductivityApp - Sign Up";
	}, [isRegisterSuccessful]);

	return (
		<div className='d-flex justify-content-center align-items-center min-vh-100'>
			<Container>
				{isRegisterSuccessful ? (
					<Col sm='10' md='8' lg='6'>
						<div className='text-center'>
							<h3>
								You registered successfully! Now you can log in your account, <Link to='/sign-in'>sign in</Link>.
							</h3>
						</div>
					</Col>
				) : (
					<Col sm='10' md='8' lg='6' xl='5' xxl='4'>
						<div className='mb-4 text-center'>
							<h2>Sign Up</h2>
							<p>Register new account to improve your life.</p>
						</div>
						<Form onSubmit={handleUserRegistartion}>
							<Form.Group className='mb-3'>
								<Form.Floating>
									<Form.Control value={userData.name} onChange={e => setUserData(prevState => ({ ...prevState, name: e.target.value }))} type='text' placeholder='User name' />
									<Form.Label>User name</Form.Label>
								</Form.Floating>
							</Form.Group>

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
							{errorsArray && (
								<div className="mb-3">
									<div className='alert alert-danger'>
										{errorsArray.map((error, index) => (
											<div key={index}>{error}</div>
										))}
									</div>
								</div>
							)}
							<Button className='w-100' type='submit'>
								Create account
							</Button>
						</Form>

						<div className='mt-4 d-flex justify-content-center'>
							<p className='me-1'>Already have an account?</p>
							<Link to='/sign-in'>Sign in</Link>.
						</div>
					</Col>
				)}
			</Container>
		</div>
	);
}
