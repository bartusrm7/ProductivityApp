import { useState } from "react";
import { MdDownloadDone } from "react-icons/md";

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

	async function handleCountCurrentStreakDays() {
		try {
			const jwt = localStorage.getItem("jwt");
			const response = await fetch("http://productivityapp.local/count-current-streak-days", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: `Bearer ${jwt}`,
				},
				body: JSON.stringify({ id: habitId }),
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
		handleCountCurrentStreakDays();
		handleCountAmountDaysDone();
	}

	return (
		<>
			<button className="action-btn success-action-btn me-2" onClick={handleServeAllMethods}>
				<MdDownloadDone size={24} />
			</button>

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
