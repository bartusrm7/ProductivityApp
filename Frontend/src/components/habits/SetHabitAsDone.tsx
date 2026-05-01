import { useState } from "react";
import { Button } from "react-bootstrap";

export default function SetHabitAsDone({ habitId, checkCurrentDay }: { habitId: number; checkCurrentDay: Date }) {
	const [errorsArray, setErrorsArray] = useState<string[]>([]);

	async function handleSetHabitAsDone() {
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/set-habit-done", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify({ id: habitId, checkCurrentDay: checkCurrentDay }),
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
		<>
			<Button onClick={handleSetHabitAsDone}>X</Button>

			{errorsArray.length > 0 && (
				<div>
					{errorsArray.map((error, index) => (
						<div key={index} className='alert alert-danger'>
							{error}
						</div>
					))}
				</div>
			)}
		</>
	);
}
