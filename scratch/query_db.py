import pymysql

try:
    connection = pymysql.connect(
        host='127.0.0.1',
        user='shakshak_user',
        password='your_password',
        database='shakshak_db',
        charset='utf8mb4',
        cursorclass=pymysql.cursors.DictCursor
    )

    with connection.cursor() as cursor:
        print("--- SERVICES IN DB ---")
        cursor.execute("SELECT id, title, service_type, enable, intercity_type FROM services")
        services = cursor.fetchall()
        for s in services:
            print(f"ID: {s['id']} | Title: {s['title']} | Type: {s['service_type']} | Enabled: {s['enable']} | Intercity: {s['intercity_type']}")

        print("\n--- SERVICE MODELS IN DB ---")
        cursor.execute("SELECT id, service_id, model_name, min_year FROM service_models")
        models = cursor.fetchall()
        for m in models:
            print(f"ID: {m['id']} | ServiceID: {m['service_id']} | Model: {m['model_name']} | Min Year: {m['min_year']}")

        print("\n--- FREIGHT VEHICLES IN DB ---")
        cursor.execute("SELECT id, name, description, km_charge, length, width, height, enable FROM freight_vehicles")
        freights = cursor.fetchall()
        for f in freights:
            print(f"ID: {f['id']} | Name: {f['name']} | Desc: {f['description']} | Charge/km: {f['km_charge']} | Enabled: {f['enable']}")

except Exception as e:
    print("Error querying database:", e)
finally:
    if 'connection' in locals() and connection.open:
        connection.close()
