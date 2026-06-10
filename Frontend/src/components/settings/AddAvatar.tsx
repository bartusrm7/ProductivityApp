import { useEffect, useState } from "react";
import { Button, Form, Modal } from "react-bootstrap";

export default function AddAvatar({ refreshParent, refreshData }: { refreshParent: number; refreshData: () => void }) {
	const [avatarData, setAvatarData] = useState<File | null>(null);
	const [showModal, setShowModal] = useState<boolean>(false);
	const [errorsArray, setErrorsArray] = useState<string[]>([]);

	const handleOpenModal = () => {
		setShowModal(true);
		setAvatarData(avatarData);
	};

	const handleCloseModal = () => {
		setShowModal(false);
	};

	async function handleUpdateAvatar(e: any) {
		e.preventDefault();
		try {
			if (!avatarData) {
				setErrorsArray(["No file selected"]);
				return;
			}
			const formData = new FormData();
			formData.append("avatar", avatarData);

			if (!formData) {
				setErrorsArray(["Error with form data type"]);
			}

			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/set-avatar", {
				method: "POST",
				headers: {
					Authorization: `Bearer ${jwt}`,
				},
				body: formData,
			});
			const data = await response.json();
			if (data.success) {
				refreshData();
				setShowModal(false);
			} else {
				setErrorsArray(data.data);
			}
		} catch (error) {
			setErrorsArray(["Server error. Try again."]);
		}
	}

	useEffect(() => {}, [refreshParent]);

	return (
		<div>
			<Button className='custom-btn' onClick={handleOpenModal}>
				Upload avatar
			</Button>

			<Modal show={showModal} onHide={handleCloseModal}>
				<Modal.Header closeButton>
					<Modal.Title>Edit current Note</Modal.Title>
				</Modal.Header>
				<Modal.Body>
					<Form onSubmit={handleUpdateAvatar}>
						<Form.Group className='mb-3'>
							<Form.Control onChange={e => setAvatarData((e.target as HTMLInputElement).files?.[0] || null)} type='file' />
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
							Edit
						</Button>
					</Form>
				</Modal.Body>
			</Modal>
		</div>
	);
}
