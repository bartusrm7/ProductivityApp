import { useState } from "react";
import { Button, Form, Modal } from "react-bootstrap";
import type { UserSettingsData } from "../../types/settings";

export default function UpdateUserName() {
	const [userData, setUserData] = useState<UserSettingsData>({ id: 0, name: "", password: "" });
	const [showModal, setShowModal] = useState<boolean>(false);
	const [errorsArray, setErrorsArray] = useState<string[]>([]);

	const handleOpenModal = () => {
		setShowModal(true);
		setUserData(userData);
	};

	const handleCloseModal = () => {
		setShowModal(false);
	};

	async function handleUpdateUserName(e: any) {
		e.preventDefault();
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/update-user-name", {
				method: "POST",
				headers: {
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify(userData),
			});
			const data = await response.json();
			if (data.errors) {
				setErrorsArray(data.errors);
			}
		} catch (error) {
			setErrorsArray(["Server error. Try again."]);
		}
	}

	return (
		<div>
			<Button className='custom-btn' onClick={handleOpenModal}>
				Change user name
			</Button>

			<Modal show={showModal} onHide={handleCloseModal}>
				<Modal.Header closeButton>
					<Modal.Title>Change user name</Modal.Title>
				</Modal.Header>
				<Modal.Body>
					<Form onSubmit={handleUpdateUserName}>
						<Form.Group className='mb-3'>
							<Form.Floating>
								<Form.Control value={userData.name} onChange={e => setUserData(prevState => ({ ...prevState, name: e.target.value }))} type='text' placeholder='' />
								<Form.Label>User name</Form.Label>
							</Form.Floating>
						</Form.Group>

						{errorsArray.length > 0 && (
							<div>
								{errorsArray.map((error, index) => (
									<div key={index} className='alert alert-danger'>
										{error}
									</div>
								))}
							</div>
						)}

						<Button className='custom-btn w-100' type='submit'>
							Change
						</Button>
					</Form>
				</Modal.Body>
			</Modal>
		</div>
	);
}
