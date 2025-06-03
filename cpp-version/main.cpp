 #include <iostream>
#include <string>
#include <vector>
#include <fstream>
#include <algorithm>
#include <iomanip>
#include <queue>     
#include <stack>
#ifdef _WIN32
#include <windows.h>
#endif

using namespace std;


struct OrderRecord {
    string name;
    string product;
    int quantity;
    double bill;
    string type; 
};


struct DeliveryTask {
    string name;
    string product;
    int quantity;
    double bill;
    string address;
    double deliveryCharges;
    int distance;

    DeliveryTask(string n, string p, int q, double b, string a, double dC, int dist)
        : name(n), product(p), quantity(q), bill(b), address(a), deliveryCharges(dC), distance(dist) {}
};


struct CompareDistance {
    bool operator()(DeliveryTask const& d1, DeliveryTask const& d2) {
        return d1.distance > d2.distance;
    }
};

class User {
public:
    string name;
    string product;

    int quantity;
    double bill;

    User(string name, string product, int quantity, double bill) : name(name), product(product), quantity(quantity), bill(bill) {}
};

class DeliveryUser : public User {
public:
    string address;
    double deliveryCharges;
    int distanceDelivery;
    DeliveryUser* next;

    DeliveryUser(string name, string product, int quantity, double bill,
        string address, double deliveryCharges, int distanceDelivery): User(name, product, quantity, bill),address(address), deliveryCharges(deliveryCharges),
        distanceDelivery(distanceDelivery),next(nullptr) {}
};

class Shop {
public:
    string name;
    vector<string> products;
    vector<int> prices;
    string address;
    DeliveryUser* nextDeliveryUser;

    Shop(string name, string address)
        : name(name), address(address), nextDeliveryUser(nullptr) {}
};

class TakeAway {
public:
    string name;
    string product;
    int quantity;
    double bill;
    int orderId;
    TakeAway* left;
    TakeAway* right;

    TakeAway(string name, string product, int quantity, double bill, int orderId)
        : name(name), product(product), quantity(quantity), bill(bill), orderId(orderId), left(nullptr), right(nullptr) {}
};

class EcommerceWarehouseSystem {
private:
    Shop* shop;
    TakeAway* root;
    vector<pair<string, string>> users;    
    vector<pair<int, string>> feedbacks;   
    stack<OrderRecord> recentOrders;
    queue<string> customerComplaints;

    priority_queue<DeliveryTask, vector<DeliveryTask>, CompareDistance> deliveryQueue;


    void display(TakeAway* root) {
        if (root) {
            display(root->left);
            cout << "\n----------------------------------" << endl;
            cout << "Name: " << root->name << endl;
            cout << "Product: " << root->product << endl;
            cout << "Quantity: " << root->quantity << endl;
            cout << "Bill: " << fixed << setprecision(2) << root->bill << endl;
            cout << "Order ID: " << root->orderId << endl;
            cout << "-----------------------------------\n" << endl;
            display(root->right);
        }
    }

    int height(TakeAway* root) {
        if (!root) {
            return -1;
        }

        return 1 + max(height(root->left), height(root->right));
    }

    int balanceFactor(TakeAway* root) {
        if (!root) {
            return 0;
        }

        return height(root->left) - height(root->right);
    }

    TakeAway* rotateLeft(TakeAway* root) {

        TakeAway* newRoot = root->right;
        root->right = newRoot->left;
        newRoot->left = root;
        return newRoot;
    }

    TakeAway* rotateRight(TakeAway* root) {

        TakeAway* newRoot = root->left;
        root->left = newRoot->right;
        newRoot->right = root;
        return newRoot;
    }

    TakeAway* balance(TakeAway* root) {

        int bf = balanceFactor(root);
        if (bf > 1) {
            if (balanceFactor(root->left) < 0) root->left = rotateLeft(root->left);
            return rotateRight(root);
        }
        if (bf < -1) {
            if (balanceFactor(root->right) > 0) root->right = rotateRight(root->right);
            return rotateLeft(root);
        }
        return root;
    }

    TakeAway* insert(TakeAway* root, string name, string product, int quantity, double bill, int orderId) {

        if (!root) {
            return new TakeAway(name, product, quantity, bill, orderId);
        }

        if (orderId < root->orderId) {
            root->left = insert(root->left, name, product, quantity, bill, orderId);
        }
        else if (orderId > root->orderId) {
            root->right = insert(root->right, name, product, quantity, bill, orderId);
        }
        else {
            cout << "No duplicate values allowed" << endl;
            return root;
        }
        return balance(root);
    }

    TakeAway* minValueNode(TakeAway* root) {

        TakeAway* current = root;

        while (current && current->left) {
            current = current->left;
        }
        return current;
    }

    TakeAway* deleteNode(TakeAway* root, int orderId) {

        if (!root) {
            return root;
        }
        if (orderId < root->orderId) {
            root->left = deleteNode(root->left, orderId);
        }
        else if (orderId > root->orderId) {
            root->right = deleteNode(root->right, orderId);
        }
        else {
            if (!root->left) {
                TakeAway* temp = root->right;
                delete root;
                return temp;
            }
            if (!root->right) {
                TakeAway* temp = root->left;
                delete root;
                return temp;
            }
            TakeAway* temp = minValueNode(root->right);
            root->orderId = temp->orderId;
            root->right = deleteNode(root->right, temp->orderId);
        }
        return balance(root);
    }

    TakeAway* search(TakeAway* root, int orderId) {

        if (!root || root->orderId == orderId) {
            return root;
        }
        if (orderId < root->orderId) {
            return search(root->left, orderId);
        }
        return search(root->right, orderId);
    }

    void placeOrderHomeDelivery(string name, string product, int quantity, double bill, string address, int deliveryCharges, int distanceDelivery) {

        DeliveryUser* newDeliveryUser = new DeliveryUser(name, product, quantity, bill, address, deliveryCharges, distanceDelivery);

        if (!shop->nextDeliveryUser) {
            shop->nextDeliveryUser = newDeliveryUser;
        }
        else {
            DeliveryUser* temp = shop->nextDeliveryUser;
            while (temp->next) temp = temp->next;
            temp->next = newDeliveryUser;
        }

        ofstream file("home_delivery_orders.txt", ios::app);

        if (file.is_open()) {
            file << name << " " << product << " " << quantity << " " << bill << " " << address << " " << deliveryCharges << " " << distanceDelivery << endl;
            file.close();
        }

        cout << "\n**Order Details";
        cout << "\nCustomer Name: " << name << "\nOrder Name: " << product << "\nQuantity: " << quantity;
        cout << "\nTotal Bill: " << fixed << setprecision(2) << bill;
        cout << "\nAddress: " << address << "\nDistance in km: " << distanceDelivery;
        cout << "\nDelivery Charges: " << deliveryCharges << endl;
        recentOrders.push(OrderRecord{ name, product, quantity, bill, "Delivery" });

    }

    void displayAllOrdersHomeDelivery() {

        if (!shop->nextDeliveryUser) {
            cout << "There is no Order for Home Delivery Customer till yet" << endl;
        }
        else {
            DeliveryUser* temp = shop->nextDeliveryUser;
            while (temp) {
                cout << "-----------------------------------------------------" << endl;
                cout << "Home Delivery Customer : " << temp->name << endl;
                cout << "Product Name : " << temp->product << endl;
                cout << "Quantity : " << temp->quantity << endl;
                cout << "Delivery Charges : " << temp->deliveryCharges << "KM" << endl;
                cout << "Delivery Distance : " << temp->distanceDelivery << endl;
                cout << "Bill : " << temp->bill << " RS/-" << endl;
                cout << "Delivery Address : " << temp->address << endl;
                cout << "-----------------------------------------------------" << endl;
                temp = temp->next;
            }
        }
    }

    void addProduct(string product, int price) {

        shop->products.push_back(product);
        shop->prices.push_back(price);
        ofstream file("products.txt", ios::app);
        if (file.is_open()) {
            file << product << " " << price << endl;
            file.close();
        }
        cout << "Product added successfully!" << endl;
    }

    void updateProduct(int index, string product, int price) {

        if (index < 0 || index >= shop->products.size()) {
            cout << "Invalid product index!" << endl;
            return;
        }
        shop->products[index] = product;
        shop->prices[index] = price;
        saveProductsToFile();
        cout << "Product updated successfully!" << endl;
    }

    void deleteProduct(int index) {

        if (index < 0 || index >= shop->products.size()) {
            cout << "Invalid product index!" << endl;
            return;
        }
        shop->products.erase(shop->products.begin() + index);
        shop->prices.erase(shop->prices.begin() + index);
        saveProductsToFile();
        cout << "Product deleted successfully!" << endl;
    }

    void leaveFeedback(int orderId, string feedback) {
        feedbacks.push_back(make_pair(orderId, feedback));
        ofstream file("feedbacks.txt", ios::app);
        if (file.is_open()) {
            file << orderId << " " << feedback << endl;
            file.close();
        }
        cout << "Feedback submitted successfully!" << endl;
    }

    void displayFeedbacks() {
        if (feedbacks.empty()) {
            cout << "No feedbacks available!" << endl;
            return;
        }
        for (const auto& fb : feedbacks) {
            cout << "Order ID: " << fb.first << " - Feedback: " << fb.second << endl;
        }
    }

    bool authenticate(string username, string password) {
        for (const auto& user : users) {
            if (user.first == username && user.second == password) {
                return true;
            }
        }
        return false;
    }

    void registerUser(string username, string password) {
        for (const auto& user : users) {
            if (user.first == username) {
                cout << "Username already exists!" << endl;
                return;
            }
        }
        users.push_back(make_pair(username, password));
        ofstream file("users.txt", ios::app);
        if (file.is_open()) {
            file << username << " " << password << endl;
            file.close();
        }
        cout << "User registered successfully!" << endl;
    }

    void loadUsers() {
        ifstream file("users.txt");
        if (file.is_open()) {
            string username, password;
            while (file >> username >> password) {
                users.push_back(make_pair(username, password));
            }
            file.close();
        }
    }

    void loadProducts() {
        ifstream file("products.txt");
        if (file.is_open()) {
            string product;
            int price;
            while (file >> product >> price) {
                shop->products.push_back(product);
                shop->prices.push_back(price);
            }
            file.close();
        }
    }

    void saveProductsToFile() {
        ofstream file("products.txt");
        if (file.is_open()) {
            for (size_t i = 0; i < shop->products.size(); ++i) {
                file << shop->products[i] << " " << shop->prices[i] << endl;
            }
            file.close();
        }
    }

    void saveTakeAwayOrderToFile(string name, string product, int quantity, double bill, int orderId) {
        ofstream file("take_away_orders.txt", ios::app);
        if (file.is_open()) {
            file << name << " " << product << " " << quantity << " " << bill << " " << orderId << endl;
            file.close();
        }
    }

    void loadDeliveryQueueFromList() {
        while (!deliveryQueue.empty()) deliveryQueue.pop();  

        DeliveryUser* temp = shop->nextDeliveryUser;
        while (temp) {
            deliveryQueue.push(DeliveryTask(temp->name, temp->product, temp->quantity, temp->bill,
                temp->address, temp->deliveryCharges, temp->distanceDelivery));
            temp = temp->next;
        }
    }

    void processDeliveriesByDistance() {
        loadDeliveryQueueFromList();

        if (deliveryQueue.empty()) {
            cout << "No deliveries to process.\n";
            return;
        }

        cout << "\nProcessing Deliveries by Distance (Min-Heap Priority Queue)\n";
        while (!deliveryQueue.empty()) {
            DeliveryTask current = deliveryQueue.top();
            deliveryQueue.pop();

            cout << "\n--------------------------------------\n";
            cout << "Customer: " << current.name << endl;
            cout << "Product: " << current.product << endl;
            cout << "Quantity: " << current.quantity << endl;
            cout << "Total Bill: " << current.bill << endl;
            cout << "Address: " << current.address << endl;
            cout << "Distance: " << current.distance << " km\n";
            cout << "Delivery Charges: " << current.deliveryCharges << " Rs\n";
            cout << "--------------------------------------\n";
        }
    }


public:
    EcommerceWarehouseSystem() {
        shop = new Shop("WareHouse", "Bahria University, Islamabad");
        root = nullptr;
        loadUsers();
        loadProducts();
    }

    void clearScreen() {
        #ifdef _WIN32
        system("cls");
        #else
        system("clear");
        #endif
    }

    void prompt(const string& message) {
        cout << "\033[1;36m" << message << "\033[0m";  
    }

    void pauseScreen() {
        cout << "\n\033[1;36mPress Enter to continue...\033[0m";
        cin.ignore();
        cin.get();
    }

    void printHeader(const string& title) {
        clearScreen();
        cout << "\033[1;34m";  
        cout << "========================================\n";
        cout << "        " << title << "\n";
        cout << "========================================\n\n";
        cout << "\033[0m";
    }

    void showWelcomeScreen() {
        clearScreen();
        cout << "\033[1;35m";
        cout << "\n\n\n\n\n\n";
        cout << "====================================================================================================================\n";
        cout << endl;
        cout << "                                          WELCOME TO SK LOGISTICS SOLUTIONS                                         \n";
        cout << endl;
        cout << "====================================================================================================================\n";
        cout << endl;
        cout << "                                              Developed by Sana & Kashaf                                            \n";
        cout << endl;
        cout << "====================================================================================================================\n";
        cout << "\033[0m";
        pauseScreen();
    }

    void run() {
        showWelcomeScreen();

        int choice;
        string username, password;

        while (true) {
            printHeader("User Menu");
            cout << "\033[1;35m1.\033[0m Register\n";
            cout << "\033[1;35m2.\033[0m Login\n";
            cout << "\033[1;35m3.\033[0m Exit\n\n";
            prompt("Enter your choice: ");
            cin >> choice;

            if (choice == 1) {
                printHeader("User Registration");
                prompt("Enter username: ");
                cin >> username;
                prompt("Enter password: ");
                cin >> password;
                registerUser(username, password);
                pauseScreen();
            }
            else if (choice == 2) {
                printHeader("User Login");
                prompt("Enter username: ");
                cin >> username;
                prompt("Enter password: ");
                cin >> password;
                if (authenticate(username, password)) {
                    cout << "\n\033[1;32m✅ Login successful!\033[0m\n";
                    pauseScreen();
                    break;
                }
                else {
                    cout << "\033[1;31m❌ Invalid credentials! Please try again.\033[0m\n";
                    pauseScreen();
                }
            }
            else if (choice == 3) {
                cout << "\033[1;33m✨ Exiting the program. Goodbye! ✨\033[0m\n";
                return;
            }
            else {
                cout << "\033[1;31mInvalid choice! Please try again.\033[0m\n";
                pauseScreen();
            }
        }

        do {
            printHeader(shop->name + " - Main Menu");
            cout << "\033[1;35m1.\033[0m  Display Products\n";
            cout << "\033[1;35m2.\033[0m  Place TakeAway Order\n";
            cout << "\033[1;35m3.\033[0m  Place Home Delivery Order\n";
            cout << "\033[1;35m4.\033[0m  Collect TakeAway Order\n";
            cout << "\033[1;35m5.\033[0m  Display Delivery Orders\n";
            cout << "\033[1;35m6.\033[0m  Display TakeAway Orders\n";
            cout << "\033[1;35m7.\033[0m  Add Product\n";
            cout << "\033[1;35m8.\033[0m  Update Product\n";
            cout << "\033[1;35m9.\033[0m  Delete Product\n";
            cout << "\033[1;35m10.\033[0m Leave Feedback\n";
            cout << "\033[1;35m11.\033[0m Display Feedbacks\n";
            cout << "\033[1;35m12.\033[0m Process Deliveries by Distance\n";
            cout << "\033[1;35m13.\033[0m View Recent Orders\n";
            cout << "\033[1;35m14.\033[0m Register Complaint\n";
            cout << "\033[1;35m15.\033[0m View All Complaints\n";
            cout << "\033[1;35m0.\033[0m  Exit\n\n";

            prompt("Enter your choice: ");
            cin >> choice;

            string name, product, address, feedback;
            int quantity, productNumber, orderId, distance, deliveryCharges, price, index;
            double bill;

            switch (choice) {
            case 1:
                printHeader("Available Products");
                cout << "\033[1;33m--------------------------------------------------------------\n";
                cout << " | ITEM NO. |          ITEM NAME         |    PRICE (Rs)     |\n";
                cout << "--------------------------------------------------------------\033[0m";
                for (size_t i = 0; i < shop->products.size(); ++i) {
                    cout << "\n |   " << setw(3) << i + 1 << "    | " << setw(25) << left << shop->products[i]
                        << "| " << setw(10) << right << shop->prices[i] << "     |";
                }
                cout << "\n\033[1;33m--------------------------------------------------------------\033[0m\n";
                pauseScreen();
                break;

            case 2:
                printHeader("Place TakeAway Order");
                prompt("Enter your name: ");
                cin >> name;
                prompt("Enter product number: ");
                cin >> productNumber;
                prompt("Enter quantity: ");
                cin >> quantity;
                prompt("Enter Order ID: ");
                cin >> orderId;
                bill = quantity * shop->prices[productNumber - 1];
                root = insert(root, name, shop->products[productNumber - 1], quantity, bill, orderId);
                saveTakeAwayOrderToFile(name, shop->products[productNumber - 1], quantity, bill, orderId);
                recentOrders.push(OrderRecord{ name, shop->products[productNumber - 1], quantity, bill, "TakeAway" });
                cout << "\033[1;32m\n✅ Order placed successfully!\033[0m\n";
                cout << "Total Bill: " << bill << " Rs/-\nOrder ID: " << orderId << "\n";
                pauseScreen();
                break;

            case 3:
                printHeader("Place Home Delivery Order");
                prompt("Enter your name: ");
                cin >> name;
                prompt("Enter product number: ");
                cin >> productNumber;
                if (productNumber < 1 || productNumber > shop->products.size()) {
                    cout << "\033[1;31m❌ Invalid product number!\033[0m\n";
                    pauseScreen();
                    break;
                }
                prompt("Enter quantity: ");
                cin >> quantity;
                prompt("Enter address: ");
                cin.ignore();
                getline(cin, address);
                prompt("Enter distance (in km): ");
                cin >> distance;
                deliveryCharges = 20 * distance;
                bill = quantity * shop->prices[productNumber - 1] + deliveryCharges;
                placeOrderHomeDelivery(name, shop->products[productNumber - 1], quantity, bill, address, deliveryCharges, distance);
                pauseScreen();
                break;

            case 4:
                printHeader("Collect TakeAway Order");
                prompt("Enter Order ID: ");
                cin >> orderId;
                if (search(root, orderId)) {
                    root = deleteNode(root, orderId);
                    cout << "\033[1;32m✅ Order ready. Please collect it.\033[0m\n";
                }
                else {
                    cout << "\033[1;31m❌ Order not found.\033[0m\n";
                }
                pauseScreen();
                break;

            case 5:
                printHeader("Delivery Orders");
                displayAllOrdersHomeDelivery();
                pauseScreen();
                break;

            case 6:
                printHeader("TakeAway Orders");
                display(root);
                pauseScreen();
                break;

            case 7:
                printHeader("Add Product");
                prompt("Enter product name: ");
                cin.ignore();
                getline(cin, product);
                prompt("Enter price: ");
                cin >> price;
                addProduct(product, price);
                pauseScreen();
                break;

            case 8:
                printHeader("Update Product");
                prompt("Enter product index to update: ");
                cin >> index;
                prompt("Enter new product name: ");
                cin.ignore();
                getline(cin, product);
                prompt("Enter new price: ");
                cin >> price;
                updateProduct(index - 1, product, price);
                pauseScreen();
                break;

            case 9:
                printHeader("Delete Product");
                prompt("Enter product index to delete: ");
                cin >> index;
                deleteProduct(index - 1);
                pauseScreen();
                break;

            case 10:
                printHeader("Leave Feedback");
                prompt("Enter Order ID: ");
                cin >> orderId;
                prompt("Enter feedback: ");
                cin.ignore();
                getline(cin, feedback);
                leaveFeedback(orderId, feedback);
                pauseScreen();
                break;

            case 11:
                printHeader("All Feedbacks");
                displayFeedbacks();
                pauseScreen();
                break;

            case 12:
                printHeader("Processing Deliveries");
                processDeliveriesByDistance();
                pauseScreen();
                break;

            case 13:
                printHeader("Recent Orders");
                if (recentOrders.empty()) {
                    cout << "\033[1;33mNo recent orders.\033[0m\n";
                }
                else {
                    stack<OrderRecord> temp = recentOrders;
                    while (!temp.empty()) {
                        OrderRecord ord = temp.top(); temp.pop();
                        cout << "Customer: " << ord.name << ", Product: " << ord.product
                            << ", Qty: " << ord.quantity << ", Bill: " << ord.bill
                            << ", Type: " << ord.type << endl;
                    }
                }
                pauseScreen();
                break;

            case 14:
                printHeader("Register Complaint");
                prompt("Enter your complaint: ");
                cin.ignore();
                getline(cin, feedback);
                customerComplaints.push(feedback);
                cout << "\033[1;32m✅ Complaint registered.\033[0m\n";
                pauseScreen();
                break;

            case 15:
                printHeader("Customer Complaints");
                if (customerComplaints.empty()) {
                    cout << "\033[1;33mNo complaints registered.\033[0m\n";
                }
                else {
                    queue<string> temp = customerComplaints;
                    int c = 1;
                    while (!temp.empty()) {
                        cout << c++ << ". " << temp.front() << endl;
                        temp.pop();
                    }
                }
                pauseScreen();
                break;

            case 0:
                cout << "\033[1;33m✨ Thank you for using our services! ✨\033[0m\n";
                break;

            default:
                cout << "\033[1;31m❌ Invalid choice!\033[0m\n";
                pauseScreen();
            }
        } while (choice != 0);
    }


};



int main() {
    EcommerceWarehouseSystem eWarehouse;
    eWarehouse.run();

    return 0;
}
