import { useState } from "react";
import { Button } from "react-bootstrap";

export default function SetHabitAsDone({ habitId, amountDaysDone }: { habitId: number; amountDaysDone: number }) {
	const [errorsArray, setErrorsArray] = useState<string[]>([]);

	async function handleSetHabitAsDone() {
		const now = new Date();
		const year = now.getUTCFullYear();
		const month = now.getUTCMonth();
		const day = now.getUTCDate();

		const today = new Date(Date.UTC(year, month, day, 0, 0, 0));
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/set-habit-done", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify({ id: habitId, checkCurrentDay: today.toISOString() }),
			});
			const data = await response.json();
			if (data.errors) {
				setErrorsArray(data.errors);
			}
		} catch (error) {
			setErrorsArray(["Server error. Try again."]);
		}
	}

	async function handleCountAmountDaysDone() {
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/count-amount-days-done", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify({ id: habitId, amountDaysDone: amountDaysDone }),
			});
			const data = await response.json();
			if (data.errors) {
				setErrorsArray(data.errors);
			}
		} catch (error) {
			setErrorsArray(["Server error. Try again."]);
		}
	}

	async function handleServeAllMethods() {
		handleSetHabitAsDone();
		handleCountAmountDaysDone();
	}

	return (
		<>
			<Button onClick={handleServeAllMethods}>X</Button>

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
